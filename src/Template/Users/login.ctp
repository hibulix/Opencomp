<?php

echo $this->Form->create('User', array(
    'class'=>'form-signin',
    'inputDefaults' => array(
        'div'=>'',
        'class' => 'form-control',
        'wrapInput' => '',
    )
));

echo $this->Form->input('username',array(
    'label' =>false,
    'autocomplete'=>'off',
    'placeholder'=>"Nom d'utilisateur",
    'class'=>'form-control top focus',
    'prepend' => '<i class="fa fa-user fa fa-2x"></i>',
));

echo $this->Form->input('password',array(
    'label'=>false,
    'autocomplete'=>"off",
    'placeholder'=>"Mot de passe",
    'class'=>"bottom",
    'prepend' => '<i class="fa fa-lock fa fa-2x"></i>',
)); ?>

<div class="spacer"></div>
<div class="yubikey input-group" style="display:none;">

<?php echo $this->Form->input('yubikeyOTP',array(
    'label' =>false,
    'autocomplete'=>"off",
    'placeholder'=>"YubikeyOTP",
    'value'=>"",
    'prepend' => $this->Html->image("yubikey.png", array("height"=>30,"alt" => "Yubikey logo")),
)); ?>

</div>

<?php echo $this->Form->button('<i class="fa fa-sign-in"></i>   '.__('Se connecter'), array('type' => 'submit','class'=>'btn btn-lg btn-primary btn-block submit'));
echo $this->Form->end();

$this->start('script'); ?>
<script type="text/javascript">
$('.focus').focus();
</script>
<?php $this->end();

?>

<script type="text/javascript">
$('.submit').click(function(e) {
    $.ajax({
        url: "<?php echo $this->Url->build(array("controller" => "users", "action" => "needYubikeyToken")); ?>",
        type: "POST",
        data: $('.form-signin').serializeArray(),
        beforeSend: function() {
            var submit = $('.submit');
            submit.attr('disabled','disabled');
            submit.removeClass('btn-primary');
            submit.html('<i class="fa fa-circle-o-notch fa-spin"></i> Connexion');
        }
    }).done(function(data) {
        if(data == 'true'){
            $('.center').html('Insérez votre Yubikey et effleurez le disque de métal.');
            submit.hide();
            $('.login').hide();
            $('.yubikey').show(1000);
            $('input[name=yubikeyOTP]').focus();
        }else{
            $('.form-signin').submit();
        }
    });
    e.preventDefault();
});

$('input[name=yubikeyOTP]').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        $('.form-signin').submit();
    }
});
</script>