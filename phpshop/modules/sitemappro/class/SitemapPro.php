<?php

class SitemapPro
{
    private $options = [];
    private $xml = '';

    // seourl modules
    private $isSeoUrlEnabled = false;
    private $isSeoUrlProEnabled = false;
    private $isSeoNewsEnabled = false;
    private $isSeoPagesEnabled = false;
    private $isSeoBrandsEnabled = false;

    public function __construct()
    {
        $orm = new PHPShopOrm('phpshop_modules_sitemappro_system');

        $this->options = $orm->select();

        // ���� ������ SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
            $this->isSeoUrlEnabled = true;
        }

        // ���� ������ SEOURLPRO
        if (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
            $this->isSeoUrlProEnabled = true;

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system']);
            $settings = $PHPShopOrm->select(['seo_news_enabled, seo_page_enabled', 'seo_brands_enabled'], ['id' => "='1'"]);
            if($settings['seo_news_enabled'] == 2)
                $this->isSeoNewsEnabled = true;
            if($settings['seo_page_enabled'] == 2)
                $this->isSeoPagesEnabled = true;
            if($settings['seo_brands_enabled'] == 2)
                $this->isSeoBrandsEnabled = true;

            include_once dirname(dirname(__DIR__)) .'/seourlpro/inc/option.inc.php';
        }
    }

    public function generateSitemap($ssl = false)
    {
        // ��������� �����, ����� �������.
        if((int) $this->options['is_products_step'] === 0) {
            $this->addMainPage($ssl);
            $this->addPages($ssl);
            $this->addNews($ssl);
            $this->addCategories($ssl);

            if($this->isSeoUrlProEnabled && $this->isSeoBrandsEnabled) {
                $this->addBrands($ssl);
            }

            $this->xml .= '</urlset>';

            $orm = new PHPShopOrm('phpshop_modules_sitemappro_system');
            $orm->update(['is_products_step_new' => '1'], ['id' => '="1"']);
        }
        // ��������� ��������� �������.
        else {
            $this->xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            $this->addProducts($ssl);

            $this->xml .= '</urlset>';
        }

        $this->compile($ssl);
    }

    private function addProducts($ssl)
    {
        $from = (int) $this->options['processed_products'];
        $to = (int) $this->options['limit_products'];

        // ����������
        $queryMultibase = $this->productsMultibase();

        $orm = new PHPShopOrm();
        $result = $orm->query("select * from " . $GLOBALS['SysValue']['base']['products'] . " where $queryMultibase enabled='1' and parent_enabled='0' and price>0 limit $from, $to");

        $orm = new PHPShopOrm('phpshop_modules_sitemappro_system');
        // ������� ������ ��� �����, ������ ������ �����������. �������� ���������, ��� �� ������� ������� ������.
        if(mysqli_num_rows($result) < (int) $this->options['limit_products']) {
            $orm->update(['is_products_step_new' => '0', 'processed_products_new' => '0'], ['id' => '="1"']);
        } else {
            $orm->update(['processed_products_new' => (int) $this->options['processed_products'] + (int) $this->options['limit_products']], ['id' => '="1"']);
        }

        while ($row = mysqli_fetch_assoc($result)) {

            $this->xml .= '<url>' . "\n";

            // ����������� ���
            $url = 'shop/UID_' . $row['id'];

            // SEOURL
            if (!empty($this->isSeoUrlEnabled))
                $url.= '_' . PHPShopString::toLatin($row['name']);

            //  SEOURLPRO
            if ($this->isSeoUrlProEnabled) {
                if (empty($row['prod_seo_name']))
                    $url = 'id/' . $GLOBALS['PHPShopSeoPro']->setLatin($row['name']) . '-' . $row['id'];
                else
                    $url = 'id/' . $row['prod_seo_name'] . '-' . $row['id'];
            }

            $this->xml .= '<loc>' . $this->getSiteUrl($ssl) . $url . '.html</loc>' . "\n";
            $this->xml .= '<lastmod>' . PHPShopDate::dataV($row['datas'], false, true) . '</lastmod>' . "\n";
            $this->xml .= '<changefreq>daily</changefreq>' . "\n";
            $this->xml .= '<priority>1.0</priority>' . "\n";
            $this->xml .= '</url>' . "\n";
        }
    }

    private function addMainPage($ssl)
    {
        $this->xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $this->xml .= '<url>' . "\n";
        $this->xml .= '<loc>'.$this->getSiteUrl($ssl) . '</loc>' . "\n";
        $this->xml .= '<changefreq>weekly</changefreq>' . "\n";
        $this->xml .= '<priority>1.0</priority>' . "\n";
        $this->xml .= '</url>' . "\n";
    }

    private function addPages($ssl)
    {
        $where = [
            'enabled' => "!='0'",
            'category' => "!=2000"
        ];

        // ����������
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
        $data = $PHPShopOrm->getList(['*'], $where, ['order' => 'datas DESC']);

        foreach ($data as $row) {
            $this->xml .= '<url>' . "\n";
            $this->xml .= '<loc>'. $this->getSiteUrl($ssl) . 'page/' . $row['link'] . '.html</loc>' . "\n";
            $this->xml .= '<lastmod>' . PHPShopDate::dataV($row['datas'], false, true) . '</lastmod>' . "\n";
            $this->xml .= '<changefreq>weekly</changefreq>' . "\n";
            $this->xml .= '<priority>1.0</priority>' . "\n";
            $this->xml .= '</url>' . "\n";
        }

        // �������� ��������
        unset($where);
        $where = ['parent_to' => '=0'];

        // ����������
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['parent_to'] .= ' and (servers ="" or servers REGEXP "i1000i")';
        else
            $where = null;

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page_categories']);
        $data = $PHPShopOrm->getList(['*'], $where);

        foreach ($data as $row) {
            // ����������� url
            $url = 'page/CID_' . $row['id'];

            if ($this->isSeoUrlEnabled)
                $url = 'page/CID_' . $row['id'] . '_' . PHPShopString::toLatin($row['name']);

            //  SEOURLPRO
            if ($this->isSeoUrlProEnabled && $this->isSeoPagesEnabled) {
                if (empty($row['page_cat_seo_name']))
                    $url = 'page/' . PHPShopString::toLatin($row['name']);
                else
                    $url = 'page/' . $row['page_cat_seo_name'];
            }

            $this->xml .= '<url>' . "\n";
            $this->xml .= '<loc>' . $this->getSiteUrl($ssl) . $url . '.html</loc>' . "\n";
            $this->xml .= '<changefreq>weekly</changefreq>' . "\n";
            $this->xml .= '<priority>0.5</priority>' . "\n";
            $this->xml .= '</url>' . "\n";
        }
    }

    private function addNews($ssl)
    {
        $where['datau'] = '<' . time();

        // ����������
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['datau'].= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
        $data = $PHPShopOrm->getList(['*'], $where,['order' => 'datas DESC']);

        foreach ($data as $row) {

            // ����������� url
            $url = 'news/ID_' . $row['id'];

            if ($this->isSeoUrlEnabled)
                $url = 'news/ID_' . $row['id'] . '_' . PHPShopString::toLatin($row['zag']);

            //  SEOURLPRO
            if ($this->isSeoUrlProEnabled && $this->isSeoNewsEnabled) {
                if (empty($row['news_seo_name']))
                    $url = 'news/' . PHPShopString::toLatin($row['zag']);
                else
                    $url = 'news/' . $row['news_seo_name'];
            }

            $this->xml .= '<url>' . "\n";
            $this->xml .= '<loc>'. $this->getSiteUrl($ssl) . $url . '.html</loc>' . "\n";
            $this->xml .= '<lastmod>' . PHPShopDate::dataV(PHPShopDate::GetUnixTime($row['datas']), false, true) . '</lastmod>' . "\n";
            $this->xml .= '<changefreq>daily</changefreq>' . "\n";
            $this->xml .= '<priority>0.5</priority>' . "\n";
            $this->xml .= '</url>' . "\n";
        }
    }

    private function addCategories($ssl)
    {
        $where['skin_enabled'] = "!='1'";

        // ����������
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $data = $PHPShopOrm->getList(['*'], $where);

        foreach ($data as $row) {

            // ����������� ���
            $url = 'shop/CID_' . $row['id'];

            // SEOURL
            if ($this->isSeoUrlEnabled)
                $url.= '_' . PHPShopString::toLatin($row['name']);

            //  SEOURLPRO
            if ($this->isSeoUrlProEnabled) {
                if (empty($row['cat_seo_name']))
                    $url = str_replace("_", "-", PHPShopString::toLatin($row['name']));
                else
                    $url = $row['cat_seo_name'];
            }

            $this->xml .= '<url>' . "\n";
            $this->xml .= '<loc>' . $this->getSiteUrl($ssl) . $url . '.html</loc>' . "\n";
            $this->xml .= '<changefreq>weekly</changefreq>' . "\n";
            $this->xml .= '<priority>0.5</priority>' . "\n";
            $this->xml .= '</url>' . "\n";
        }
    }

    private function addBrands($ssl)
    {
        $brandsOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
        $brandsIds = [];
        $result = $brandsOrm->getList(['id'], ['brand' => '="1"']);
        foreach ($result as $value) {
            $brandsIds[] = $value['id'];
        }

        if(count($brandsIds) > 0) {
            $brandValuesOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);

            $brandValues = $brandValuesOrm->getList(['sort_seo_name'], [
                'category' => sprintf(' IN(%s)', implode(',', $brandsIds)),
                'sort_seo_name' => '<> ""'
            ]);

            foreach ($brandValues as $brandValue) {
                $this->xml .= '<url>' . "\n";
                $this->xml .= '<loc>'. $this->getSiteUrl($ssl) . 'brand/' . $brandValue['sort_seo_name'] . '.html</loc>' . "\n";
                $this->xml .= '<changefreq>weekly</changefreq>' . "\n";
                $this->xml .= '<priority>0.5</priority>' . "\n";
                $this->xml .= '</url>' . "\n";
            }
        }
    }

    private function getSiteUrl($ssl = false)
    {
        $protocol = 'http://';
        if($ssl) {
            $protocol = 'https://';
        }

        return $protocol . $_SERVER['SERVER_NAME'] . '/';
    }

    private function productsMultibase()
    {
        // ����������
        if (defined("HostID") or defined("HostMain")) {

            $multi_cat = [];

            // �� �������� ������� ��������
            $where['skin_enabled '] = "!='1'";

            if (defined("HostID"))
                $where['servers'] = " REGEXP 'i" . HostID . "i'";
            elseif (defined("HostMain"))
                $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $data = $PHPShopOrm->getList(['id'], $where);

            foreach ($data as $row) {
                $multi_cat[] = $row['id'];
            }

            return ' category IN (' . @implode(',', $multi_cat) . ') and ';
        }
    }

    private function compile($ssl)
    {
        $files = [];

        $host = '';
        if (defined("HostID"))
            $host = '_' . HostID;

        if((int) $this->options['is_products_step'] === 0) {
            $file = sprintf('sitemap%s_1', $host);
        } else {
            $index = (((int) $this->options['processed_products'] + (int) $this->options['limit_products']) / (int) $this->options['limit_products']) + 1;
            $file = sprintf('sitemap%s_%s', $host, $index);
        }

        // ������ � ����
        fwrite(fopen(dirname(dirname(dirname(dirname(__DIR__)))) . sprintf('/UserFiles/Files/%s.xml', $file), "w+"), $this->xml);

        for ($fileIndex = 1; file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . sprintf('/UserFiles/Files/sitemap%s_%s.xml', $host, $fileIndex)); $fileIndex++) {
            $files[] = $this->getSiteUrl($ssl) . sprintf('UserFiles/Files/sitemap%s_%s.xml', $host, $fileIndex);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'. "\n";
        foreach ($files as $file) {
            $xml .= '<sitemap><loc>' . $file . '</loc></sitemap>'. "\n";
        }
        $xml .= '</sitemapindex>';

        // ��������� ���� ������ �� ����� �����
        fwrite(fopen(dirname(dirname(dirname(dirname(__DIR__)))) . sprintf('/sitemap%s.xml', $host), "w+"), $xml);
    }
}
