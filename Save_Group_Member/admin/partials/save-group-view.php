<?php $post_id = get_the_ID(); ?>

    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

<?php

$i = 0;

while ($result = get_post_meta($post_id, "role".$i, true)) {

    $image_id = get_post_meta($post_id, "imageId" . $i, true);
    $image_src = get_custom_image($post_id, "imageId" . $i);

    $role = get_post_meta($post_id, "role" . $i, true);
    $name = get_post_meta($post_id, "name" . $i, true);
    $lastname = get_post_meta($post_id, "lastname" . $i, true);
    $description = get_post_meta($post_id, "description" . $i, true);

    ?>

    <div class="row member-container" member-index="<?php echo $i; ?>">
        <div class="col-md-2 col-sm-2 col-xs-2 img-container">
            <img alt="<?php $role . ' ' . $name . ' ' . $lastname ?>" src="<? echo $image_src; ?>">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10 text-container">
            <h1><label name="role"><?php echo $role; ?></label></h1>
            <label>
                <span name="name"><?php echo $name; ?></span>&nbsp;
                <span name="lastname"><?php echo $lastname; ?></span>
            </label>
            <p><?php echo $description; ?></p>
            <div class="modifyGroupMember"><a onclick="return false;" href="#">Modifica</a></div>
            <input type="hidden" name="image-id" class="image-file" value="<?php echo $image_id; ?>">
        </div>
    </div>

    <?

    $i++;

}

?>


<div class="row member-container" style="display: none;">
    <div class="col-md-2 col-sm-2 col-xs-2 img-container">
        <img alt="">
    </div>
    <div class="col-md-10 col-sm-10 col-xs-10 text-container">
        <h1><label name="role"></label></h1>
        <label>
            <span name="name"></span>&nbsp;
            <span name="lastname"></span>
        </label>
        <p name="description"></p>
        <input type="hidden" name="image-id">
        <div class="modifyGroupMember"><a onclick="return false;" href="#">Modifica</a></div>
    </div>
</div>

<div class="form-group-member">
    <div class="row">
        <label class="col-sm-2 col-lg-2">Ruolo</label>
        <div class="col-sm-10 col-lg-10 inputs">
            <input type="text" class="form-control" name="role" placeholder="Inserisci il ruolo..."/>
        </div>
    </div>

    <div class="row">
        <label class="col-sm-2 col-lg-2 control-label">Nome</label>
        <div class="col-sm-10 col-lg-10 inputs">
            <input type="text" class="form-control" name="name" placeholder="Inserisci il nome..."/>
        </div>
    </div>

    <div class="row">
        <label class="col-sm-2 col-lg-2 control-label">Cognome</label>
        <div class="col-sm-10 col-lg-10 inputs">
            <input type="text" class="form-control" name="lastname" placeholder="Inserisci il cognome..."/>
        </div>
    </div>

    <div class="row">
        <label class="col-sm-2 col-lg-2">Foto</label>
        <div class="col-sm-10 col-lg-10 inputs">
            <span class="upload-btn-wrapper">
                <input class="btn" value="Sfoglia..." type="button">
                <input type="file" name="image-member" class="image-file" accept="image/*"/>
            </span>
            <span class="loading"><img class="img-responsive" style="width: 30px; height: auto;" src="<?=get_template_directory_uri(); ?>/images/loading.gif"></span>
        </div>
    </div>

    <div class="row">
        <label class="col-sm-2 col-lg-2">Descrizione</label>
        <div class="col-sm-10 col-lg-10 inputs">
            <textarea class="form-control" name="description" placeholder="Inserisci la descrizione..."></textarea>
        </div>
    </div>

    <input type="hidden" name="image-id">

    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <input type="button" class="btn btn-primary" name="saveGroupMember" value="Salva"/>
        </div>
    </div>
</div>