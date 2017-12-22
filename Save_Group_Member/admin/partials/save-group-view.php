<?php $post_id = get_the_ID(); ?>

<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

<?php

for ($i = 0; $i < count($group); $i++) {
    $image_id = get_post_meta($post_id, "imageId" . $i, true);
    $image_src = get_custom_image($post_id, "imageId" . $i);

    $role = $group[$i];

    $name = get_post_meta($post_id, "name" . $i, true);
    if ($name == "") $name = "Nome";

    $lastname = get_post_meta($post_id, "lastname" . $i, true);
    if ($lastname == "") $lastname = "Cognome";

    $description = get_post_meta($post_id, "description" . $i, true);
    if ($lastname == "") $lastname = "Da inserire";

    ?>

    <div class="member-container" member-index="<?php echo $i; ?>">
        <div class="img-container">
            <img alt="<?php $role . ' ' . $name . ' ' . $lastname ?>" src="<? echo $image_src; ?>">
        </div>
        <div class="text-container">
            <h1>
                <span class="role" name="role"><?php echo $role; ?></span>
            </h1>
            <label>
                <span name="name"><?php echo $name; ?></span>&nbsp;
                <span name="lastname"><?php echo $lastname; ?></span>
            </label>
            <p><?php echo $description; ?></p>
            <div class="modifyGroupMember">
                <a class="modifyMember" onclick="return false;" href="#">Modifica</a>
                <a id="deleteMember" onclick="return false;" style="display: none;" href="#">Elimina</a>
            </div>
            <input type="hidden" name="image-id" class="image-file" value="<?php echo $image_id; ?>">
        </div>
    </div>

    <?
}

?>

<div class="form-group-member" style="display: none;">
    <div class="row">
        <span class="label" style="padding: 6px 0;">Ruolo</span>
        <div class="inputs">
            <h1>
                <span class="role" name="role"></span>
            </h1>
        </div>
    </div>

    <div class="row">
        <span class="label" style="padding: 6px 0;">Nome</span>
        <div class="inputs">
            <input type="text" class="form-control" name="name" placeholder="Inserisci il nome..."/>
        </div>
    </div>

    <div class="row">
        <span class="label" style="padding: 6px 0;">Cognome</span>
        <div class="inputs">
            <input type="text" class="form-control" name="lastname" placeholder="Inserisci il cognome..."/>
        </div>
    </div>

    <div class="row">
        <span class="label">Foto</span>
        <div class="inputs">
            <div class="btn">Sfoglia</div>
            <input type="file" name="image-member" class="image-file" accept="image/*"/>
            <span class="loading"><img class="img-responsive" style="width: 30px; height: auto;" src="<?=get_template_directory_uri(); ?>/images/loading.gif"></span>
        </div>
    </div>

    <div class="row">
        <span class="label">Descrizione</span>
        <div class="inputs">
            <textarea class="form-control" name="description" placeholder="Inserisci la descrizione..."></textarea>
        </div>
    </div>

    <input type="hidden" name="image-id">

    <div class="row">
        <input type="button" class="btn btn-primary" name="saveGroupMember" value="Salva"/>
    </div>
</div>