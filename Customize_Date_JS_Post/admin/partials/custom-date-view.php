<?php

wp_nonce_field(basename(__FILE__), "meta-box-nonce");

$post_meta_date = get_post_meta(get_the_ID(), "meta-box-date", true);

if (!$post_meta_date)
    $post_meta_date = current_time('mysql');

$post_meta_date = date('d-m-Y H:i', strtotime($post_meta_date));

?>

<div class="input-group date form_datetime col-md-12">
    <input type="date" id="datepicker" name="meta-box-date" value="<?php echo $post_meta_date; ?>">
</div>