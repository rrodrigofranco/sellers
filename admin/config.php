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
        document.getElementById(Status).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
<div class="wrap">
    <h2>Analisando os Sellers</h2>
</div>

<!-- Tab links -->
<div class="tab">
    <button class="tablinks" onclick="openSeller(event, 'All')">All</button>
    <button class="tablinks active" onclick="openSeller(event, 'Active')">Active</button>
    <button class="tablinks" onclick="openSeller(event, 'Inactive')">Inactive</button>
</div>
<?php

$vendors = get_users(array('role__in' => array('author', 'seller'))); //Get only the Sellers
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
<?php
list_products()
?>
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


add_action('woocommerce_single_product_summary', 'wc_product_sold_count', 11);
function wc_product_sold_count()
{
    global $product;
    $units_sold = get_post_meta($product->id, 'total_sales', true);
    //echo '<p>' . sprintf(__('Units Sold: %s', 'woocommerce'), $units_sold) . '</p>';
}

function list_products()
{
    $product_query = dokan()->product->all();
    var_dump($product_query);
}

//add_action('woocommerce_single_product_summary', 'wp_product_sold_count', 11);
function wp_product_sold_count()
{
    global $product;
    $total_sold = get_post_meta($product->get_id(), 'total_sales', true);
    if ($total_sold)
        echo '' . sprintf(__('Total Sold: %s', 'woocommerce'), $total_sold) . '';
}
