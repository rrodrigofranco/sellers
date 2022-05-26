<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>

<script>
    function updateButton() {
        aux = jQuery(".tablinks");
        for (let i = 0; i < aux.size(); i++) {
            if (aux[i].className == 'tablinks active') {
                aux[i].click();
                i = jQuery(".tablinks").size();
            }
        }
    }

    function openSeller(evt, Status) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        if (document.getElementById("products").checked) {
            Status = Status + 'Products';
        }

        document.getElementById(Status).style.display = "block";
        evt.currentTarget.className += " active";
    }
    window.onload = (event) => {
        jQuery('input[type=radio][name=sellers]').change(() => {
            jQuery("input:radio[name=products]:checked")[0].checked = false;
            updateButton();
        });

        jQuery('input[type=radio][name=products]').change(() => {
            jQuery("input:radio[name=sellers]:checked")[0].checked = false;
            updateButton();
        });
    }
</script>
<div class="wrap">
    <h2>Analisando os Sellers</h2>
</div>
<form>
    <input type="radio" name="sellers" id="sellers" checked>
    <label for="sellers">
        Sellers       
    </label>
    <input type="radio" name="products" id="products">
    <label for="products">
        Products
    </label>
</form>


<!-- Tab links -->
<div class="tab">
    <button class="tablinks" onclick="openSeller(event, 'All')">All</button>
    <button class="tablinks active" onclick="openSeller(event, 'Active')">Active</button>
    <button class="tablinks" onclick="openSeller(event, 'Inactive')">Inactive</button>
</div>
<?php

$vendors = get_users(array('role__in' => array('author', 'seller'))); //Get only the Sellers
global $wpdb;
?>

<!-- Tab content -->
<div id="All" class="tabcontent">
    <h3>All</h3>
    <?php

    foreach ($vendors as $user) {
        if (has_product($user->id)) {
            $produtos = 'ATIVO';
        } else {
            $produtos = 'INATIVO';
        }
    ?>
        <p><a href="<? echo get_home_url() ?>/wp-admin/edit.php?post_type=product&author=<?php echo $user->id ?>" target="_blank"><?php echo esc_html($user->display_name) ?></a> -> <?php echo $produtos ?> <br></p>
    <?php
    }
    ?>
</div>

<div id="AllProducts" class="tabcontent">
    <h3>All</h3>
    <?php
    $all_product_data = $wpdb->get_results("SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'");
    foreach ($all_product_data as $produto) {
        $url = get_permalink($produto->ID);
        if (my_units_sold_count($produto->ID)) {
            $produtos = 'ATIVO';
        } else {
            $produtos = 'INATIVO';
        }
    ?>
        <p><a href="<?php echo $url ?>" target="_blank"><?php echo esc_html($produto->post_title) ?></a> -> <?php echo $produtos ?> <br></p>
    <?php
    }
    ?>
</div>

<div id="Active" class="tabcontent" style="display: block;">
    <h3>Active</h3>
    <?php
    foreach ($vendors as $user) {
        if (has_product($user->id)) {
    ?>
            <p><a href="<? echo get_home_url() ?>/wp-admin/edit.php?post_type=product&author=<?php echo $user->id ?>" target="_blank"><?php echo esc_html($user->display_name) ?></a> -> ATIVO <br></p>
        <?php
        }
        ?>
    <?php
    }
    ?>
</div>

<div id="ActiveProducts" class="tabcontent" style="display: block;">
    <h3>Active</h3>
    <?php
    $all_product_data = $wpdb->get_results("SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'");
    foreach ($all_product_data as $produto) {
        $url = get_permalink($produto->ID);
        if (my_units_sold_count($produto->ID)) {
    ?>
            <p><a href="<?php echo $url ?>" target="_blank"><?php echo esc_html($produto->post_title) ?></a> -> ATIVO <br></p>
    <?php
        }
    }
    ?>
</div>


<div id="Inactive" class="tabcontent">
    <h3>Inactive</h3>
    <?php
    foreach ($vendors as $user) {
        if (!has_product($user->id)) {
    ?>
            <p><a href="<? echo get_home_url() ?>/wp-admin/edit.php?post_type=product&author=<?php echo $user->id ?>" target="_blank"><?php echo esc_html($user->display_name) ?></a> -> INATIVO <br></p>
        <?php
        }
        ?>
    <?php
    }
    ?>

</div>

<div id="InactiveProducts" class="tabcontent">
    <h3>Inactive</h3>
    <?php
    $all_product_data = $wpdb->get_results("SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'");
    foreach ($all_product_data as $produto) {
        $url = get_permalink($produto->ID);
        if (!my_units_sold_count($produto->ID)) {
    ?>
            <p><a href="<?php echo $url ?>" target="_blank"><?php echo esc_html($produto->post_title) ?></a> -> INATIVO <br></p>
    <?php
        }
    }
    ?>
</div>

<?php


function has_product($user_id)
{

    $args = array(
        'author'         => $user_id,
    );

    $product_query = dokan()->product->all($args);

    if ($product_query->have_posts()) {

        return true;
    }
    return false;
}

function my_units_sold_count($id)
{
    $units_sold = get_post_meta($id, 'total_sales', true);
    return $units_sold;
}
