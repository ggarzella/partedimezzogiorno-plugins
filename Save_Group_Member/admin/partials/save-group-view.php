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

    <div class="member-container" member-index="<?php echo $i; ?>">
        <div class="img-container">
            <img alt="<?php $role . ' ' . $name . ' ' . $lastname ?>" src="<? echo $image_src; ?>">
        </div>
        <div class="text-container">
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


<div class="member-container" style="display: none;">
    <div class="img-container">
        <img alt="">
    </div>
    <div class="text-container">
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
        <div class="label">Ruolo</div>
        <div class="inputs">
            <input type="text" class="form-control" name="role" placeholder="Inserisci il ruolo..."/>
        </div>
    </div>

    <div class="row">
        <div class="label">Nome</div>
        <div class="inputs">
            <input type="text" class="form-control" name="name" placeholder="Inserisci il nome..."/>
        </div>
    </div>

    <div class="row">
        <div class="label">Cognome</div>
        <div class="inputs">
            <input type="text" class="form-control" name="lastname" placeholder="Inserisci il cognome..."/>
        </div>
    </div>

    <div class="row">
        <div class="label">Foto</div>
        <div class="inputs">
            <div class="btn">Sfoglia</div>
            <input type="file" name="image-member" class="image-file" accept="image/*"/>
            <span class="loading"><img class="img-responsive" style="width: 30px; height: auto;" src="<?=get_template_directory_uri(); ?>/images/loading.gif"></span>
        </div>
    </div>

    <div class="row">
        <div class="label">Descrizione</div>
        <div class="inputs">
            <textarea class="form-control" name="description" placeholder="Inserisci la descrizione..."></textarea>
        </div>
    </div>

    <input type="hidden" name="image-id">

    <div class="row">
        <div>
            <input type="button" class="btn btn-primary" name="saveGroupMember" value="Salva"/>
        </div>
    </div>
</div>