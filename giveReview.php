<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>

<div style="width:80%; margin:auto;">
    <form name="register" action="addReview.php" method="post">
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Give a Review</span>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="productName">Topic:</label>
            <div class="col-sm-9">
                <input class="form-control" name="productName" id="productName" type="text" required />
                (required)
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="ranking">Ranking (How many Stars):</label>
            <div class="col-sm-9">
                <select class="form-control" name="ranking" id="ranking" required>
                    <?php
                    // Loop to generate options for the dropdown from 1 to 5
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
                (required)
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="feedback">Feedback:</label>
            <div class="col-sm-9">
                <textarea class="form-control" name="feedback" id="feedback" cols="25" rows="4"></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit">Submit</button>
            </div>
        </div>
    </form>
</div>

<?php
// Include the Page Layout footer
include("footer.php");
?>
