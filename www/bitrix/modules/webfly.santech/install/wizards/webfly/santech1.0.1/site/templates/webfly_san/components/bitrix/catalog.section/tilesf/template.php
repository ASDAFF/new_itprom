<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$Fav = new wfHighLoadBlock("#HLBLOCK_FAVS#");
$favList = $Fav->elemGet();
$favIds = array();
foreach($favList as $fv){
  $favIds[$fv["ID"]] = $fv["UF_FAV_ID"];
}
$arResult["FAVS"] = array_flip($favIds);
if (!empty($arResult['ITEMS'])) {
  CJSCore::Init(array("popup"));
  if($arParams["DISPLAY_TOP_PAGER"]) {
    echo $arResult["NAV_STRING"];
  }
  $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
  $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
  $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
  $propsList = array("BRAND_REF", "MANUFACTURER", "MATERIAL_VANNY", "COLOR", "SHAPE_BATH");
  $list = $APPLICATION->GetCurPageParam("view=list");
  $tiles = $APPLICATION->GetCurPageParam("",array("view"));
  if(!empty($_GET["sort_ord"])){
    if($_GET["sort_ord"] == "asc") $srt = "desc";
    else $srt = "asc";
    $sort = $APPLICATION->GetCurPageParam("sort_ord={$srt}",array("sort_ord"));
  }else{
    $sort = $APPLICATION->GetCurPageParam("sort=price&sort_ord=asc");
  }
  ?>
  <div class="sort-block">
    <span class="sort-title"><?=GetMessage("WF_PRODUCT_SORT_NAME")?></span>
    <a href="<?=$sort?>" class="link-sort link-price"><?=GetMessage("WF_PRODUCT_SORT_PRICE")?></a>
    <!--a href="#" class="link-sort link-brand ls-asc" data-sort="asc"><?=GetMessage("WF_PRODUCT_SORT_BRAND")?></a-->
    <a href="<?=$list?>" class="link-list"><?=GetMessage("WF_PRODUCT_VIEW_LIST")?></a>
    <a href="<?=$tiles?>" class="link-table"><?=GetMessage("WF_PRODUCT_VIEW_TABLE")?></a>
    <?//wfDump($arResult["ITEMS"][0]["COMPARE_URL"])?>
  </div>
  <div class="product-catalog product-catalog03" id="wf-product-catalog">
    <ul>
      <?
      foreach($arResult["ITEMS"] as $key => $arItem):
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
        $strMainID = $this->GetEditAreaId($arItem['ID']);
        $isOffers = count($arItem["OFFERS"])>0;
        $arItemIDs = array(
          'ID' => $strMainID,
          'PICT' => $strMainID . '_pict',
          'SECOND_PICT' => $strMainID . '_secondpict',
          'QUANTITY' => $strMainID . '_quantity',
          'QUANTITY_DOWN' => $strMainID . '_quant_down',
          'QUANTITY_UP' => $strMainID . '_quant_up',
          'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
          'BUY_LINK' => $strMainID . '_buy_link',
          'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
          'PRICE' => $strMainID . '_price',
          'DSC_PERC' => $strMainID . '_dsc_perc',
          'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
          'PROP_DIV' => $strMainID . '_sku_tree',
          'PROP' => $strMainID . '_prop_',
          'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
          'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
        );
        $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
        $strTitle = isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem['NAME'];
        $discPrice = ceil($arItem['MIN_PRICE']['DISCOUNT_VALUE']);
        $minPrice = ceil($arItem['MIN_PRICE']['VALUE']);
        ?>
        <li>
          <div class="hold" id="<?=$strMainID?>">
            <div class="visual">
              <a id="<?= $arItemIDs['PICT']; ?>" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                <img class="sm-img" src="<?= $arItem['PREVIEW_PICTURE_SM']['src'];?>" width="<?= $arItem['PREVIEW_PICTURE_SM']['width'];?>" height="<?= $arItem['PREVIEW_PICTURE_SM']['height'];?>" title="<?=$strTitle;?>" alt="<?=$strTitle;?>" />
                <div class="labels">
                  <?if(!empty($arItem["PROPERTIES"]["NEWPRODUCT"]["VALUE_ENUM_ID"])):?><span class="new"><?=$arItem["PROPERTIES"]["NEWPRODUCT"]["NAME"]?></span><?endif;?>
                  <?if(!empty($arItem["PROPERTIES"]["SALELEADER"]["VALUE_ENUM_ID"])):?><span class="hit"><?=$arItem["PROPERTIES"]["SALELEADER"]["NAME"]?></span><?endif;?>
                  <?if(!empty($arItem["PROPERTIES"]["SPECIALOFFER"]["VALUE_ENUM_ID"])or($minPrice > $discPrice)):?><span class="sale"><?=$arItem["PROPERTIES"]["SPECIALOFFER"]["NAME"]?></span><?endif;?>
                </div>
              </a>
            </div>
            <div class="block">
              <div class="description">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="product-title"><?=$arItem["NAME"]?></a>
              </div>
              <div class="wrap-block">
                <div class="box">
                  <div class="col-left">
                    <?
                    if($minPrice > $discPrice):?>
                      <span class="price" id="<?=$arItemIDs['PRICE']?>"><span class="my-digit"><?=$discPrice?></span>&nbsp;<span class="rouble">&#8399;</span>
                        <noindex><span class="oldPrice"><?=$minPrice?>&nbsp;</span></noindex>
                      </span>
                    <?else:?>
                      <span class="price" id="<?=$arItemIDs['PRICE']?>"><span class="my-digit"><?=$minPrice?></span>&nbsp;<span class="rouble">&#8399;</span></span>
                    <?endif;?>
                  </div>
                  <?if($arItem["CAN_BUY"]):?>
                    <a id="<?=$arItemIDs['BUY_LINK']?>" href="javascript:void(0);" class="link-basket<?if($isOffers):?> no-animation<?endif?>" rel="nofollow"><?=GetMessage("CT_BCS_TPL_MESS_BTN_BUY")?></a>
                  <?else:?>
                    <a href="#" class="link-question">?</a>
                  <?endif;?>
              </div>
            </div>
            <form class="dop_options" method="post" action="" style="margin-top:10px;">
              <input type="checkbox" id="ch7<?=$key?>" class="srav checkbox" name="my_srav[]" data-count="sravCount" wf-elem-id="<?=$arItem["ID"]?>" value="<?=$arItem["~COMPARE_URL"]?>" />
              <label for="ch7<?=$key?>" class="myChb hitro-label"><?=GetMessage("CT_BCS_TPL_MESS_BTN_COMPARE")?></label>
              <input type="checkbox" id="ch8<?=$key?>" class="fav checkbox" name="my_fav[]" data-count="favCount" value="<?=$arResult["FAVS"][$arItem["ID"]]?>" elem-val= "<?=$arItem["ID"]?>" checked/>
              <label for="ch8<?=$key?>" class="myChb hitro-label"><?=GetMessage("WF_FAVORITES")?></label>
            </form>
            <?

            $arJSParams = array(
                'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                'SHOW_ADD_BASKET_BTN' => false,
                'SHOW_BUY_BTN' => true,
                'SHOW_ABSENT' => true,
                'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
                'SECOND_PICT' => false,
                'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                'DEFAULT_PICTURE' => array(
                    'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                    'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                ),
                'VISUAL' => array(
                    'ID' => $arItemIDs['ID'],
                    'PICT_ID' => $arItemIDs['PICT'],
                    'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                    'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                    'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                    'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                    'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                    'PRICE_ID' => $arItemIDs['PRICE'],
                    'TREE_ID' => $arItemIDs['PROP_DIV'],
                    'TREE_ITEM_ID' => $arItemIDs['PROP'],
                    'BUY_ID' => $arItemIDs['BUY_LINK'],
                    'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                    'DSC_PERC' => $arItemIDs['DSC_PERC'],
                    'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                    'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                ),
                'BASKET' => array(
                    'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'SKU_PROPS' => $arItem['OFFERS_PROP_CODES']
                ),
                'PRODUCT' => array(
                    'ID' => $arItem['ID'],
                    'NAME' => $arItem['~NAME'],
                    'CAN_BUY' => $arItem["CAN_BUY"],
                    'ADD_URL' => $arItem['~ADD_URL'],
                    'PICT' => $arItem['PREVIEW_PICTURE'],
                ),
                'OFFERS' => $arItem['JS_OFFERS'],
                'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
                'TREE_PROPS' => $arSkuProps,
                'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
            );
            ?>
            <script type="text/javascript">
              <?if($isOffers):?>
                $("#<?=$arItemIDs['BUY_LINK']?>").on("click",function(){
                  location.href = "<?=$arItem["DETAIL_PAGE_URL"]?>";
                });
              <?else:?>
                var <?= $strObName; ?> = new JCCatalogSection(<?= CUtil::PhpToJSObject($arJSParams, false, true); ?>);
              <?endif?>
            </script>
          </div>
        </li>
      <?endforeach?>
    </ul>
  </div>
  <script type="text/javascript">
    BX.message({
      MESS_BTN_BUY: '<?= ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCS_TPL_MESS_BTN_BUY')); ?>',
      MESS_BTN_ADD_TO_BASKET: '<?= ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',
      MESS_NOT_AVAILABLE: '<?= ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE')); ?>',
      BTN_MESSAGE_BASKET_REDIRECT: '<?= GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
      BASKET_URL: '<?= $arParams["BASKET_URL"]; ?>',
      ADD_TO_BASKET_OK: '<?= GetMessageJS('ADD_TO_BASKET_OK'); ?>',
      TITLE_ERROR: '<?= GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR') ?>',
      TITLE_BASKET_PROPS: '<?= GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS') ?>',
      TITLE_SUCCESSFUL: '<?= GetMessageJS('ADD_TO_BASKET_OK'); ?>',
      BASKET_UNKNOWN_ERROR: '<?= GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
      BTN_MESSAGE_SEND_PROPS: '<?= GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
      BTN_MESSAGE_CLOSE: '<?= GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE') ?>'
    });
    $(function(){
      $("body").on("change",".srav",function(){
        var url = "";
        if($(this).is(":checked")){
          url = $(this).val();
          $.post(url,{},function(d){});
        }else{
          var id = $(this).attr("wf-elem-id");
          url = "/catalog/compare/";
          var requestData = {action:"DELETE_FROM_COMPARE_RESULT",IBLOCK_ID:<?=$arParams["IBLOCK_ID"]?>,ID:[id]};
          $.get(url, requestData,function(d){console.log(d);});
        }
      });
      $("body").on("change",".fav",function(){
        var url = "<?=SITE_TEMPLATE_PATH?>/ajax/favorites.php";
        var requestData = {};
        var that = $(this);
        var elem = $(this).val();
        var elemVal = $(this).attr("elem-val");
        if($(this).is(":checked")){
          requestData = {elemId:elem, action:"add"};
          $.post(url,requestData,function(d){that.val(d);});
        }else{
          requestData = {elemId:elem, action:"delete"};
          $.post(url,requestData,function(d){
            that.val(elemVal);
            subsFav();
          });
        }
      });
    });
  </script>
  <?
}
?>