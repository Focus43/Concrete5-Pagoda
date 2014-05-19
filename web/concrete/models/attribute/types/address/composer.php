<? defined('C5_EXECUTE') or die("Access Denied."); ?>
<? $f = Loader::helper('form'); ?>
<? $co = Loader::helper('lists/countries'); ?>

<div class="ccm-attribute-address-composer-wrapper ccm-attribute-address-<?=$key->getAttributeKeyID()?>">

<div class="control-group">
	<?=$f->label($this->field('address1'), t('Address 1'))?>
	<div class="controls">
		<?=$f->text($this->field('address1'), $address1)?>
	</div>
</div>

<div class="control-group">
	<?=$f->label($this->field('address2'), t('Address 2'))?>
	<div class="controls">
		<?=$f->text($this->field('address2'), $address2)?>
	</div>
</div>

<div class="control-group">
<?=$f->label($this->field('city'), t('City'))?>
	<div class="controls">
		<?=$f->text($this->field('city'), $city)?>
	</div>
</div>

<div class="control-group ccm-attribute-address-state-province">
	<?=$f->label($this->field('state_province'), t('State/Province'))?>
<?
$spreq = $f->getRequestValue($this->field('state_province'));
if ($spreq != false) {
	$state_province = $spreq;
}
$creq = $f->getRequestValue($this->field('country'));
if ($creq != false) {
	$country = $creq;
}

?>
	<div class="controls">
		<?=$f->select($this->field('state_province_select'), array('' => t('Choose State/Province')), $state_province, array('ccm-attribute-address-field-name' => $this->field('state_province')))?>
		<?=$f->text($this->field('state_province_text'), $state_province, array('style' => 'display: none', 'ccm-attribute-address-field-name' => $this->field('state_province')))?>
	</div>
</div>

<? 

if (!$country && !$search) {
	if ($akDefaultCountry != '') {
		$country = $akDefaultCountry;
	} else {
		$country = 'US';
	}
} 

$countriesTmp = $co->getCountries();
$countries = array();
foreach($countriesTmp as $_key => $_value) {
	if ((!$akHasCustomCountries) || ($akHasCustomCountries && in_array($_key, $akCustomCountries))) {
		$countries[$_key] = $_value;
	}
}
$countries = array_merge(array('' => t('Choose Country')), $countries);
?>

<div class="control-group ccm-attribute-address-country">
<?=$f->label($this->field('country'), t('Country'))?>
<div class="controls">
<?=$f->select($this->field('country'), $countries, $country); ?>
</div>
</div>

<div class="control-group">
<?=$f->label($this->field('postal_code'), t('Postal Code'))?>
<div class="controls">
<?=$f->text($this->field('postal_code'), $postal_code)?>
</div>
</div>

</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	ccm_setupAttributeTypeAddressSetupStateProvinceSelector('ccm-attribute-address-<?=$key->getAttributeKeyID()?>');
});
//]]>
</script>