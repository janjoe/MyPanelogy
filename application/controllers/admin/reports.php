<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reports extends Survey_Common_Action {

    public function index() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Reports', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $sql = "SELECT 'pr' AS type,status_name,status_color,COUNT(*) AS tots FROM {{view_project_master}} GROUP BY status_name,status_color
                UNION ALL
                SELECT 'pl', status_name,' ',COUNT(*) AS tots FROM {{view_panel_list_master}} GROUP BY status_name";
        $drows = Yii::app()->db->createCommand($sql)->query()->readAll(); //Checked
        $aData['row'] = 0;
        $aData['drows'] = $drows;
        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (strpos($_SERVER['REQUEST_URI'], '/print')) {
            $aData['display']['header'] = false;
            $aData['display']['menu_bars'] = false;
            $aData['display']['footer'] = false;
        } else {
            $aData['display']['header'] = true;
            $aData['display']['menu_bars'] = true;
            $aData['display']['footer'] = true;
        }
        $this->_renderWrappedTemplate('reports', 'view_dashboard', $aData);
    }

    public function project() {
        $clang = Yii::app()->lang;
        if (!Permission::model()->hasGlobalPermission('Reports', 'read')) {
            Yii::app()->setFlashMessage($clang->gT("You do not have sufficient rights to access this page."), 'error');
            $this->getController()->redirect(array("admin/index"));
        }
        App()->getClientScript()->registerCssFile(Yii::app()->getConfig('styleurl') . "jquery.dataTables.css");
        App()->getClientScript()->registerScriptFile(Yii::app()->getConfig('adminscripts') . 'jquery.dataTables.min.js');
        $sql = "SELECT Company_name,AVG(IFNULL(cpc,0)) AS avg_ven_cpc, AVG(IFNULL(proj_CPC,0)) AS avg_comp_cpc
                , SUM(IFNULL(cpc*total_completed,0)) AS tot_cost, SUM(IFNULL(proj_CPC,0)) AS tot_revenues
                , SUM(IFNULL(proj_CPC*total_completed,0)-IFNULL(cpc*total_completed,0)) AS tot_profit, SUM(IFNULL(total_completed,0)) AS tot_completed
                FROM {{view_project_master_vendors}} WHERE ifnull(total_completed,0)>0 GROUP BY company_name";
        $sqlsum = ' SELECT SUM(IFNULL(tot_completed,0)) AS total_completed, SUM(IFNULL(tot_profit,0)) AS total_profit
                , SUM(IFNULL(tot_cost,0)) AS total_cost,SUM(IFNULL(tot_revenues,0)) AS total_revenues
                FROM ( ' . $sql . ' ) AS totals';
        $dr_det1 = Yii::app()->db->createCommand($sql . ' ORDER BY tot_profit ')->query()->readAll();
        $dr_sum1 = Yii::app()->db->createCommand($sqlsum)->query()->readAll();

        $sql = "SELECT sales_name,AVG(IFNULL(cpc,0)) AS avg_ven_cpc, AVG(IFNULL(proj_CPC,0)) AS avg_comp_cpc
                , SUM(IFNULL(cpc*total_completed,0)) AS tot_cost, SUM(IFNULL(proj_CPC,0)) AS tot_revenues
                , SUM(IFNULL(proj_CPC*total_completed,0)-IFNULL(cpc*total_completed,0)) AS tot_profit, SUM(IFNULL(total_completed,0)) AS tot_completed
                FROM {{view_project_master_vendors}} WHERE ifnull(total_completed,0)>0 GROUP BY sales_name";
        $dr_det2 = Yii::app()->db->createCommand($sql . ' ORDER BY tot_profit ')->query()->readAll();

        $aData['row'] = 0;
        $aData['dr_det1'] = $dr_det1;
        $aData['dr_det2'] = $dr_det2;
        $aData['dr_sum1'] = $dr_sum1;

        $aData['imageurl'] = Yii::app()->getConfig("adminimageurl");
        if (strpos($_SERVER['REQUEST_URI'], '/print')) {
            $aData['display']['header'] = false;
            $aData['display']['menu_bars'] = false;
            $aData['display']['footer'] = false;
        } else {
            $aData['display']['header'] = true;
            $aData['display']['menu_bars'] = true;
            $aData['display']['footer'] = true;
        }
        $this->_renderWrappedTemplate('reports', 'view_project', $aData);
    }

    private function _messageBoxWithRedirect($title, $message, $classMsg, $extra = "", $url = "", $urlText = "", $hiddenVars = array(), $classMbTitle = "header ui-widget-header") {
        $clang = Yii::app()->lang;
        $url = (!empty($url)) ? $url : $this->getController()->createUrl('admin/reports/index');
        $urlText = (!empty($urlText)) ? $urlText : $clang->gT("Continue");

        $aData['title'] = $title;
        $aData['message'] = $message;
        $aData['url'] = $url;
        $aData['urlText'] = $urlText;
        $aData['classMsg'] = $classMsg;
        $aData['classMbTitle'] = $classMbTitle;
        $aData['extra'] = $extra;
        $aData['hiddenVars'] = $hiddenVars;

        return $aData;
    }

    /**
     * Renders template(s) wrapped in header and footer
     *
     * @param string $sAction Current action, the folder to fetch views from
     * @param string|array $aViewUrls View url(s)
     * @param array $aData Data to be passed on. Optional.
     */
    protected function _renderWrappedTemplate($sAction = 'panellist/profilecategory', $aViewUrls = array(), $aData = array()) {
        //$aData['display']['menu_bars']['panellist'] = true;
        parent::_renderWrappedTemplate($sAction, $aViewUrls, $aData);
    }

}
