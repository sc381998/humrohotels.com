<?php
# Logged in User Profile
if (!defined('included')) {
    exit();
}
?>
<section class="error-section">
    <div class="container margin_60">
        <div class="row">
            <form action="" method="get" name="SearchForm" id="SearchForm">
                <div class="input-group col-md-8">
                    <input class="form-control" type="search" name="q" value="<?php echo @$_GET['q']; ?>" placeholder="Search..." required="" size="30">
                    <div class="input-group-btn" style="padding-left: 5px; ">
                        <button class="btn_1" type="submit" value="Search" name="Submit"><span class="icon-search"></span></button>
                    </div></div>
                <?php
                if (!empty($SID)) {
                    ?>
                    <input name="LogID" type="hidden" id="LogID" value="<?php echo $SID; ?>">
                    <?php
                }
                ?>
            </form>
        </div>

        <br />
        <?php
        if (!empty($_GET['q'])) {

            $q = trim(strip_tags($_GET['q']));

            $search = str_replace(' ', '%', $q);

            // Page Address
            $self = 'search.php?'; //$_SERVER['PHP_SELF'] . '?';
            if (!empty($_GET['page_id']) && is_numeric($_GET['page_id'])) {
                $self .= 'page_id=' . abs($_GET['page_id']);
            }

            $self .= $SID . '&q=' . $q; // page URL
            // Table
            // The Query
            $sql = "SELECT * from $ai_pagemaster where linktype = 1 and status=1 and (";



            $sql .= "link_name like \"%$search%\" or meta_key like \"%$search%\" or meta_des like \"%$search%\" or main_content like \"%$search%\"";

            //$sql = substr($sql,0,-3);

            $sql .= ") order by link_order";

            //echo $sql;

            $tsql = $sql;

            #================ Paging Starts ==================
            // how many rows to show per page 
            $rowsPerPage = 10;

            // by default we show first page 
            $pageNum = 1;

            // if $_GET['page'] defined, use it as page number 
            if (isset($_GET['page']) && is_numeric($_GET['page'])) { ###
                $pageNum = abs($_GET['page']); ###
            }

            // counting the offset 
            $offset = ($pageNum - 1) * $rowsPerPage;
            $sql .= " LIMIT $offset, $rowsPerPage";

            // Getting total records
            $tquery = $dbf->query($tsql);

            $numrows = count($tquery);


            // how many pages we have when using paging? 
            $maxPage = ceil($numrows / $rowsPerPage);

            // print the link to access each page 

            $nav = '';

            if ($pageNum >= 10) {
                $start = $pageNum - 5;
            } else {
                $start = 1;
            }

            if ($pageNum >= $maxPage - 5) {
                $end = $maxPage;
            } else {
                $end = $pageNum + 5;
            }

            for ($page = $start; $page <= $end; $page++) {
                if ($page == $pageNum) {
                    $nav .= "<span class=\"newPage\"><strong>$page</strong></span>";   // no need to create a link to current page 
                } else {
                    $nav .= " <a href=\"$self&page=$page\" class=\"newPage\">$page</a> ";
                }
            }

            // creating previous and next link 
            // plus the link to go straight to 
            // the first and last page 

            if ($pageNum > 1) {
                $page = $pageNum - 1;
                $prev = " <a href=\"$self&page=$page\"><i class=\"fa fa-angle-left\"></i></a> ";

                $first = " <a href=\"$self&page=1\"><i class=\"fa fa-angle-double-left\"></i></a> ";
            } else {
                $prev = '&nbsp;'; // we're on page one, don't print previous link 
                $first = '&nbsp;'; // nor the first page link 
            }

            if ($pageNum < $maxPage) {
                $page = $pageNum + 1;
                $next = " <a href=\"$self&page=$page\"><i class=\"fa fa-angle-right\"></i></a> ";

                $last = " <a href=\"$self&page=$maxPage\"><i class=\"fa fa-angle-double-right\"></i></a> ";
            } else {
                $next = '&nbsp;'; // we're on the last page, don't print next link 
                $last = '&nbsp;'; // nor the last page link 
            }

            // Making the Paging Variable 
            $paging = $first . $prev . $nav . $next . $last;

            # =========== End of Paging =============                    

            $query = $dbf->query($sql);

            $n = count($query);
            if ($n <= 0) {
                ?>
                <div class="error-text">
                    <h2>Sorry!! Your search did not fetch any matching Results.</h2>
                    <h3> Please try again with different keywords </h3>
                </div>
                <?php
            } else {
                // Show Paging at Top
                echo '<div class="newPaging">' . $paging . '</div>';

                // No of Results Fetched
                echo '<div id="srch_rslt">';
                echo "<h4>Your search has fetched <strong>$numrows</strong> results</h4>";

                foreach ($query as $rs) {


                    $pageLink = $rs['pagelink'] . '?' . $SID;

                    if ($rs['pagelink'] == 'index.php' && $rs['id'] != $pageid) {
                        $pageLink .= "&page_id=" . $rs['page_id'];
                    }
                    ?>
                    <div class="searchResults">
                        <h3><a href="<?php echo $pageLink; ?>">
                    <?php
                    if (strlen($rs['link_title']) > 50 && strpos($rs['link_title'], ' ', 50) > 0) {
                        $searchTitle = substr($rs['link_title'], 0, strpos($rs['link_title'], ' ', 50)) . "...";
                    } else {
                        $searchTitle = $rs['link_title'];
                    }

                    $searchTitleWords = explode(' ', $searchTitle);
                    $searchTitleRebuilt = '';

                    $qArray = explode(' ', strtolower($q));

                    foreach ($searchTitleWords as $word) {
                        $onlyWord = preg_replace('/[^a-z0-9]+/i', '', $word);
                        if (in_array(strtolower($onlyWord), $qArray)) {//if(strtolower($q) == strtolower($word)){
                            $searchTitleRebuilt .= ' <span class="highlightSearch">' . $word . '</span> ';
                        } else {
                            $searchTitleRebuilt .= " $word ";
                        }
                    }

                    echo $searchTitleRebuilt;
                    ?>
                            </a></h3>
                        <p>
                                <?php
                                $str = strip_tags($rs['main_content']);
                                if (strlen($str) > 250 && strpos($str, ' ', 250) > 0) {
                                    $searchStr = substr($str, 0, strpos($str, ' ', 250));
                                } else {
                                    $searchStr = $str;
                                }

                                $searchStrWords = explode(' ', $searchStr);
                                $searchStrRebuilt = '';

                                foreach ($searchStrWords as $word) {
                                    $onlyWord = preg_replace('/[^a-z0-9]+/i', '', $word);
                                    if (in_array(strtolower($onlyWord), $qArray)) {//if(strtolower($q) == strtolower($word)){
                                        $searchStrRebuilt .= ' <span class="highlightSearch">' . $word . '</span> ';
                                    } else {
                                        $searchStrRebuilt .= " $word ";
                                    }
                                }

                                echo $searchStrRebuilt;
                                ?>
                            ... <a href="<?php echo $pageLink; ?>">[Read Details]</a></p>
                            <?php /* ?><span class="greytext1"><a href="<?php echo $pageLink; ?>"><?php echo $pageLink; ?></a></span><?php */ ?>


                            <?php
                            // echo $gen->makedate($rs['date'] . ' ' . $rs['time'], true);
                            ?>
                        </span>
                    </div><!--searchResult-->
                            <?php
                        }
                        echo '</div>';
                        // Show Paging at Bottom
                        echo '<div class="newPaging">' . $paging . '</div>';
                    }
                }
                ?>
    </div>
</section>