<?php

include_once dirname(dirname(dirname(__DIR__))) . '/lib/thumb/phpthumb.php';

class ThumbnailImages {

    private $options = [];
    private $originalWidth;
    private $originalHeight;
    private $thumbnailWidth;
    private $thumbnailHeight;
    private $thumbnailQuality;
    private $adaptive;

    /** @var PHPShopSystem */
    private $system;

    public function __construct() {
        $orm = new PHPShopOrm('phpshop_modules_thumbnailimages_system');

        $this->options = $orm->select();
        $this->system = new PHPShopSystem();
        $this->originalWidth = !empty($this->system->getSerilizeParam('admoption.img_w')) ? (int) $this->system->getSerilizeParam('admoption.img_w') : 1000;
        $this->originalHeight = !empty($this->system->getSerilizeParam('admoption.img_h')) ? (int) $this->system->getSerilizeParam('admoption.img_h') : 1000;
        $this->originalQuality = !empty($this->system->getSerilizeParam('admoption.width_podrobno')) ? (int) $this->system->getSerilizeParam('admoption.width_podrobno') : 100;
        $this->thumbnailWidth = !empty($this->system->getSerilizeParam('admoption.img_tw')) ? (int) $this->system->getSerilizeParam('admoption.img_tw') : 300;
        $this->thumbnailHeight = !empty($this->system->getSerilizeParam('admoption.img_th')) ? (int) $this->system->getSerilizeParam('admoption.img_th') : 300;
        $this->thumbnailQuality = !empty($this->system->getSerilizeParam('admoption.width_kratko')) ? (int) $this->system->getSerilizeParam('admoption.width_kratko') : 100;
        $this->adaptive = (int) $this->system->getSerilizeParam('admoption.image_adaptive_resize') === 1;
    }

    public function generateThumbnail() {
        $count = 0;
        $skipped = [];

        foreach ($this->getImages('thumb') as $image) {
            $source = $this->getSourceImage($image);

            if (!empty($source)) {
                $thumb = new PHPThumb($source);
                $thumb->setOptions(['jpegQuality' => $this->thumbnailQuality]);

                // Адаптивность
                if (!empty($this->adaptive))
                    $thumb->adaptiveResize($this->thumbnailWidth, $this->thumbnailHeight);
                else
                    $thumb->resize($this->thumbnailWidth, $this->thumbnailHeight);

                // Ватермарк тубнейла
                if ($this->system->ifSerilizeParam('admoption.watermark_small_enabled')) {
                    $this->createWatermark($thumb);
                }

                $path = pathinfo(str_replace('_big.', '.', $source));

                // Сохранение в webp
                if ($this->options['type'] == 3 and $path['extension'] != 'webp') {

                    $thumb->setFormat('WEBP');

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $update->debug = false;
                    $image_old = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF", ".WEBP", ".webp"], ["s.png", "s.jpg", "s.jpeg", "s.gif", "s.PNG", "s.JPG", "s.JPEG", "s.GIF", "s.WEBP", "s.webp"], $image);
                    $image_new = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"], 's.webp', $image);
                    $update->update(['pic_small_new' => $image_new], ['pic_big' => '="' . $image . '"']);

                    // Удаление старого файла
                    if ($this->options['delete'] == 2)
                        @unlink($_SERVER['DOCUMENT_ROOT'].$image_old);

                    $path['extension'] = 'webp';
                }

                // Сохранение в jpg
                else if ($this->options['type'] == 2 and $path['extension'] != 'jpg' and $path['extension'] != 'jpeg') {

                    $thumb->setFormat('JPG');

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $update->debug = false;
                    $image_old = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF", ".WEBP", ".webp"], ["s.png", "s.jpg", "s.jpeg", "s.gif", "s.PNG", "s.JPG", "s.JPEG", "s.GIF", "s.WEBP", "s.webp"], $image);
                    $image_new = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF", '.WEBP', '.webp'], 's.jpg', $image);
                    $update->update(['pic_small_new' => $image_new], ['pic_big' => '="' . $image . '"']);

                    // Удаление старого файла
                    if ($this->options['delete'] == 2)
                        @unlink($_SERVER['DOCUMENT_ROOT'].$image_old);

                    $path['extension'] = 'jpg';
                }

                $thumb->save($path['dirname'] . '/' . $path['filename'] . 's.' . $path['extension']);

                $count++;
            } else {
                $skipped[] = $image;
            }
        }

        return ['count' => $count, 'skipped' => $skipped];
    }

    public function generateOriginal() {
        $count = 0;
        $skipped = [];
        foreach ($this->getImages('original') as $image) {
            $source = $this->getSourceImage($image);

            if (!empty($source)) {
                $thumb = new PHPThumb($source);
                $thumb->setOptions(['jpegQuality' => $this->originalQuality]);

                // Адаптивность
                if (!empty($this->adaptive))
                    $thumb->adaptiveResize($this->originalWidth, $this->originalHeight);
                else
                    $thumb->resize($this->originalWidth, $this->originalHeight);

                // Ватермарк оригинала
                if ($this->system->ifSerilizeParam('admoption.watermark_big_enabled')) {
                    $this->createWatermark($thumb);
                }

                $path = pathinfo(str_replace('_big.', '.', $source));


                // Сохранение в webp
                if ($this->options['type'] == 3 and $path['extension'] != 'webp') {

                    $thumb->setFormat('WEBP');

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $update->debug = false;
                    $image_new = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"], '.webp', $image);
                    $update->update(['pic_big_new' => $image_new], ['pic_big' => '="' . $image . '"']);

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                    $update->update(['name_new' => $image_new], ['name' => '="' . $image . '"']);

                    // Удаление старого файла
                    if ($this->options['delete'] == 2)
                        @unlink($_SERVER['DOCUMENT_ROOT'].$image);

                    $path['extension'] = 'webp';
                }
                // Сохранение в jpg
                elseif ($this->options['type'] == 2 and $path['extension'] != 'jpg' and $path['extension'] != 'jpeg') {

                    $thumb->setFormat('JPG');

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $update->debug = false;
                    $image_new = str_replace([".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF", '.WEBP', '.webp'], '.jpg', $image);
                    $update->update(['pic_big_new' => $image_new], ['pic_big' => '="' . $image . '"']);

                    $update = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                    $update->update(['name_new' => $image_new], ['name' => '="' . $image . '"']);

                    // Удаление старого файла
                    if ($this->options['delete'] == 2)
                        @unlink($_SERVER['DOCUMENT_ROOT'].$image);

                    $path['extension'] = 'jpg';
                }

                $thumb->save($path['dirname'] . '/' . $path['filename'] . '.' . $path['extension']);

                $count++;
            } else {
                $skipped[] = $image;
            }
        }

        return ['count' => $count, 'skipped' => $skipped];
    }

    private function getImages($operation) {
        $settings = new PHPShopOrm('phpshop_modules_thumbnailimages_system');

        $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);

        $from = (int) $this->options['processed'];
        $to = (int) $this->options['limit'];

        // Нажали кнопку генерации другого типа картинок, сбрасываем прогресс
        if ($operation !== $this->options['last_operation']) {
            $from = 0;
        }

        $images = array_column($orm->getList(['name'], false, false, ['limit' => $from . ',' . $to]), 'name');

        // Выбрано меньше чем лимит, значит картинки закончились. Обнуляем настройки, что бы процесс начался заново.
        if (count($images) < (int) $this->options['limit']) {
            $settings->update(['processed_new' => '0', 'last_operation_new' => $operation], ['id' => '="1"']);
        } else {
            $settings->update([
                'processed_new' => (int) $this->options['processed'] + (int) $this->options['limit'],
                'last_operation_new' => $operation
                    ], ['id' => '="1"']);
        }

        return $images;
    }

    private function getSourceImage($image) {
        $system = new PHPShopSystem();
        $path = pathinfo($image);

        $root = '';
        if (strpos($image, 'http:') === false && strpos($image, 'https:') === false) {
            $root = $_SERVER['DOCUMENT_ROOT'];
        }

        if ((int) $system->getSerilizeParam('admoption.image_save_source') === 1) {
            $bigImg = $path['dirname'] . '/' . $path['filename'] . '_big.' . $path['extension'];
            if (file_exists($root . $bigImg)) {
                return $root . $bigImg;
            }
        }

        if (file_exists($root . $image)) {
            return $root . $image;
        }

        return null;
    }

    private function createWatermark($image) {
        $watermarkImage = $this->system->getSerilizeParam('admoption.watermark_image');
        $watermarkText = $this->system->getSerilizeParam('admoption.watermark_text');

        // Image
        if (!empty($watermarkImage) and file_exists($_SERVER['DOCUMENT_ROOT'] . $watermarkImage))
            $image->createWatermark(
                    $_SERVER['DOCUMENT_ROOT'] . $watermarkImage, $this->system->getSerilizeParam('admoption.watermark_right'), $this->system->getSerilizeParam('admoption.watermark_bottom'), $this->system->getSerilizeParam('admoption.watermark_center_enabled')
            );
        // Text
        elseif (!empty($watermarkText))
            $image->createWatermarkText(
                    $watermarkText, $this->system->getSerilizeParam('admoption.watermark_text_size'), $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/font/' . $this->system->getSerilizeParam('admoption.watermark_text_font') . '.ttf', $this->system->getSerilizeParam('admoption.watermark_right'), $this->system->getSerilizeParam('admoption.watermark_bottom'), $this->system->getSerilizeParam('admoption.watermark_text_color'), $this->system->getSerilizeParam('admoption.watermark_text_alpha'), 0, $this->system->getSerilizeParam('admoption.watermark_center_enabled')
            );
    }

}
