<?php

/**
 * Description of setup
 *
 * @author tarak gandhi
 */
//class setupDB extends LSActiveRecord {
class setupDB {

    var $alias;

    // constructor
    function __construct($resetdb = '') {
        if ($resetdb == '123') {
            echo 'Database reseting...';
            echo '<br/><br/>';
            $this->resetDB();
        }
        echo 'Structure execution started ' . date("Y-M-d H:i:s");
        echo ' Creating Tables ';
        $this->CreateTables();
        echo ' Creating Indexes ';
        $this->CreateIndexes();
        echo ' Creating Views ';
        $this->InsertValue();
        echo ' Inserting Default Value ';
        $this->CreateViews();
        echo ' Creating SPs ';
        $this->CreateSPs();
        echo ' Structure execution completed ' . date("Y-M-d H:i:s");
    }

    //Start add_column_if_not_exist
    function add_column_if_not_exist($tbl, $column, $column_attr = "VARCHAR(255) NULL") {
        $exists = false;
        $columns = mysql_query("show columns from $tbl");
        while ($c = mysql_fetch_assoc($columns)) {
            if (strtolower($c['Field']) == strtolower($column)) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $sql = "ALTER TABLE {{$tbl}} ADD COLUMN $column  $column_attr";
            echo Yii::app()->db->execute($sql);
        }
    }

    //End add_column_if_not_exist
    //Start DropNCreateView
    function DropNCreateView($view_name, $selqry) {
        $result = Yii::app()->db->createCommand('DROP VIEW IF EXISTS {{view_' . $view_name . '}}')->query();
        //$result = Yii::app()->db->createCommand('DROP TABLE IF EXISTS {{view_' . $view_name . '}}')->query();
        $result = Yii::app()->db->createCommand('CREATE VIEW {{view_' . $view_name . '}} AS ' . $selqry)->query();
    }

    //End DropNCreateView
    //Start CreateTables
    function CreateTables() {
        //country_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{country_master}} (
        country_id INT(11) NOT NULL AUTO_INCREMENT,
        country_name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        continent VARCHAR(50) COLLATE utf8_unicode_ci DEFAULT NULL,
        IsActive tinyint DEFAULT 1,
        PRIMARY KEY (country_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //zone_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{zone_master}} (
        zone_id INT(11) NOT NULL AUTO_INCREMENT,
        zone_Name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        country_id INT(11) NOT NULL,
        IsActive tinyint DEFAULT 1,
        PRIMARY KEY (zone_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //state_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{state_master}} (
        state_id INT(11) NOT NULL AUTO_INCREMENT,
        state_Name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        zone_id INT(11) NOT NULL,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (state_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //city_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{city_master}} (
        city_id INT(11) NOT NULL AUTO_INCREMENT,
        city_Name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        state_id INT(11) NOT NULL,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (city_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //company_type_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{company_type_master}} (
        company_type_id INT(11) NOT NULL AUTO_INCREMENT,
        company_type_name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        company_type VARCHAR(1) NOT NULL,
        Istitle tinyint NOT NULL DEFAULT 0,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (company_type_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //contact_group_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{contact_group_master}} (
        contact_group_id INT(11) NOT NULL AUTO_INCREMENT,
        contact_group_name VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (contact_group_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //contact_title_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{contact_title_master}} (
        contact_title_id INT(11) NOT NULL AUTO_INCREMENT,
        contact_title_name VARCHAR(50) NOT NULL,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (contact_title_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //contact_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{contact_master}} (
            contact_id INT(11) NOT NULL AUTO_INCREMENT
          , first_name VARCHAR(25)
          , middle_name VARCHAR(25)
          , last_name VARCHAR(25)
          , company_name VARCHAR(25)
          , saluation VARCHAR(4)
          , parent_contact_id INT(11)
          , contact_group_id INT(11)
          , is_list_provider TINYINT(1)
          , notes TEXT
          , gender VARCHAR(1)
          , birth_date DATE
          , address1 VARCHAR(50)
          , address2 VARCHAR(50)
          , address3 VARCHAR(50)
          , country_id INT(11)
          , zone_id INT(11)
          , state_id INT(11)
          , city_id INT(11)
          , zip VARCHAR(15)
          , fax VARCHAR(100)
          , primary_emailid VARCHAR(100)
          , primary_contact_no VARCHAR(20)
          , other_emailid VARCHAR(100)
          , other_contact_no VARCHAR(20)
          , IsActive TINYINT(4) DEFAULT 1
          , completionlink TEXT
          , disqualifylink TEXT
          , quatafulllink TEXT  
          , RIDCheck VARCHAR(5)
          , company_id INT(11)
          , contact_type_id INT(11)
          , PRIMARY KEY (contact_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //map_company_n_types
        $sql = 'CREATE TABLE IF NOT EXISTS {{map_company_n_types}} (
        map_company_id INT(11) NOT NULL AUTO_INCREMENT,
        company_id int(11) NOT NULL,
        company_type_id int(11) NOT NULL,
        PRIMARY KEY (map_company_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //map_contact_n_titles
        $sql = 'CREATE TABLE IF NOT EXISTS {{map_contact_n_titles}} (
        map_contact_id INT(11) NOT NULL AUTO_INCREMENT,
        contact_id int(11) NOT NULL,
        contact_title_id int(11) NOT NULL,
        PRIMARY KEY (map_contact_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();


        //panel_list_master
        $sql = "CREATE TABLE IF NOT EXISTS {{panel_list_master}} (
        panel_list_id BIGINT(11) NOT NULL AUTO_INCREMENT,
        reg_no VARCHAR(255) NOT NULL,
        reg_date DATETIME NOT NULL,
        email VARCHAR(150) NOT NULL,
        password VARCHAR(150) NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        company_name VARCHAR(100) NOT NULL,
        max_no_study INT(3) NOT NULL DEFAULT '1',
        balance_no_study INT(3) NOT NULL DEFAULT '0',
        reset_study_date DATE NOT NULL,
        earn_points INT(11) NOT NULL,
        balance_points INT(11) NOT NULL,
        no_invited INT(11) NOT NULL DEFAULT '0',
        no_redirected INT(7) NOT NULL,
        no_completed INT(7) NOT NULL,
        no_disqualified INT(7) NOT NULL,
        no_qfull INT(7) NOT NULL,
        status CHAR(1) NOT NULL COMMENT 'E-Enable D-disable C-Canceled',
        remote_ip VARCHAR(50) NOT NULL,
        track_id INT(11) NOT NULL,
        s_id_1 INT(3) NOT NULL,
        s_ans_1 VARCHAR(200) NOT NULL,
        s_id_2 INT(3) NOT NULL,
        s_ans_2 VARCHAR(200) NOT NULL,
        topics INT(1) NOT NULL,
        is_complete INT(2) NOT NULL,
        is_fraud TINYINT(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (panel_list_id),
        UNIQUE KEY email (email),
        KEY is_fraud (is_fraud),
        KEY reg_date (reg_date),
        KEY status (status)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $result = Yii::app()->db->createCommand($sql)->query();

        //cms_page_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{cms_page_master}} (
        page_id INT(11) NOT NULL AUTO_INCREMENT,
        page_name VARCHAR(100) NOT NULL,
        page_title VARCHAR(200) NOT NULL,
        contenttype VARCHAR(1) NOT NULL,
        showinmenu tinyint NOT NULL DEFAULT 1,
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (page_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //cms_page_content
        $sql = 'CREATE TABLE IF NOT EXISTS {{cms_page_content}} (
        page_content_id INT(11) NOT NULL AUTO_INCREMENT,
        page_id INT(11) NOT NULL,
        language_code VARCHAR(10),
        page_content TEXT,
        meta_tags VARCHAR(250),
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (page_content_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();


        //Project_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{project_master}} (
            project_id INT(11) NOT NULL AUTO_INCREMENT
          , project_name VARCHAR(25)
          , friendly_name VARCHAR(25)
          , parent_project_id INT(11)
          , client_id INT(11)
          , client_proj_num VARCHAR(25)
          , client_ord_num VARCHAR(25)
          , contact_id INT(11)
          , manager_user_id INT(11)
          , sales_user_id INT(11)
          , country_id INT(11)
          , required_completes INT(11)
          , QuotaBuffer_Completes INT(11)
          , completes_validation INT(11)
          , cleanedup DATETIME
          , trueup DATETIME
          , closed DATETIME
          , CPC DECIMAL(7,2)
          , IR INT(11) NOT NULL DEFAULT 0
          , expected_los INT(11)
          , avg_los INT(11)
          , total_los INT(11) NOT NULL DEFAULT 0
          , reward_points INT(11)
          , client_link TEXT
          , completed_link TEXT
          , qualified_link TEXT
          , disQualified_link TEXT
          , total_redirected INT(11) NOT NULL DEFAULT 0
          , total_completed INT(11) NOT NULL DEFAULT 0
          , total_quota_full INT(11) NOT NULL DEFAULT 0
          , total_disqualify INT(11) NOT NULL DEFAULT 0
          , total_rejected INT(11) NOT NULL DEFAULT 0
          , extra_completes INT(11) NOT NULL DEFAULT 0
          , total_errors INT(11) NOT NULL DEFAULT 0
          , RIDCheck VARCHAR(5)
          , notes TEXT
          , project_status_id INT(11)
          , LastRedirected_DateTime DATETIME
          , PRIMARY KEY (project_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //project_status_master
        $sql = 'CREATE TABLE IF NOT EXISTS {{project_status_master}} (
        status_id INT(11) NOT NULL AUTO_INCREMENT,
        status_name VARCHAR(30),
        status_order INT(11),
        status_color VARCHAR(25) default "#000",
        status_for VARCHAR(1),
        status_desc_rectify VARCHAR(100),
        status_desc_unrectify VARCHAR(100),
        IsActive tinyint NOT NULL DEFAULT 1,
        PRIMARY KEY (status_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //project_master_vendors
        $sql = 'CREATE TABLE IF NOT EXISTS {{project_master_vendors}} (
        vendor_project_id INT(11) NOT NULL AUTO_INCREMENT,
        project_id INT(11),
        vendor_id INT(11),
        vendor_contact_id INT(11),
        vendor_status_id INT(11),
        notes TEXT,
        status_log TEXT,
        CPC DECIMAL(7,2) NOT NULL,
        required_completes INT(11),
        QuotaBuffer_Completes INT(11),
        completed_link TEXT,
        qualified_link TEXT,
        disQualified_link TEXT,
        QuotaFull_URL TEXT,
        max_redirects INT(11),
        total_redirected INT(11) NOT NULL DEFAULT 0,
        total_completed INT(11) NOT NULL DEFAULT 0,
        total_quota_full INT(11) NOT NULL DEFAULT 0,
        total_disqualified INT(11) NOT NULL DEFAULT 0,
        total_rejected INT(11) NOT NULL DEFAULT 0,
        extra_completed INT(11) NOT NULL DEFAULT 0,
        total_panellist INT(11) NOT NULL DEFAULT 0,
        total_keys_used INT(11) NOT NULL DEFAULT 0,
        total_keys INT(11) NOT NULL DEFAULT 0,
        total_errors INT(11) NOT NULL DEFAULT 0,
        AskOnRedirect TEXT,
        LastRedirected_DateTime DATETIME,
        created_datetime DATETIME,
        PRIMARY KEY (vendor_project_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //panellist_redirects
        $sql = 'CREATE TABLE IF NOT EXISTS {{panellist_redirects}} (
        panellist_redirect_id INT(11) NOT NULL AUTO_INCREMENT,
        vendor_project_id INT(11),
        client_id INT(11),
        panellist_id VARCHAR(255),
        vendor_id INT(11),
        redirect_status_id INT(11),
        prev_redirect_status_id int(11),
        created_datetime datetime,
        project_id INT(11),
        foreign_misc varchar(100),
        DataOnRedirect TEXT,
        total_clicked INT(11),
        StartIP varchar(45),
        EndIP varchar(45),
        CompletedOn datetime,
        LOS INT(11),
        ForeignMISC VARCHAR(255),
        Referrer TEXT,
        rectify_id int(11),
        PRIMARY KEY (panellist_redirect_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //relevant_redirects
        $sql = 'CREATE TABLE IF NOT EXISTS {{relevant_redirects}} (
        relevant_redirect_id INT(11) NOT NULL AUTO_INCREMENT,
        project_id INT(11),
        panellist_id INT(11),
        panellist_redirect_id INT(11),
        vendor_project_id  INT(11),
        redirect_status_id INT(11),
        status VARCHAR(25),
        description TEXT,
        StartIP VARCHAR(45),
        EndIP VARCHAR(45),
        RVID VARCHAR(255),
        isNew VARCHAR(10),
        score INT(4),
        country_id INT(11),
        old_id varchar(255),
        old_id_date DATETIME,
        domain VARCHAR(255),
        fraud_profile_score INT(10),
        IsMobile tinyint,
        FPF1 tinyint,
        FPF2 tinyint,
        FPF3 tinyint,
        FPF4 tinyint,
        FPF5 tinyint,
        FPF6 tinyint,
        created_datetime DATETIME,
        PRIMARY KEY (relevant_redirect_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //blocked_redirects
        $sql = 'CREATE TABLE IF NOT EXISTS {{blocked_redirects}} (
        blocked_redirect_id INT(11) NOT NULL AUTO_INCREMENT,
        panellist_id INT(11),
        panellist_redirect_id INT(11),
        vendor_project_id  INT(11),
        redirect_status_id INT(11),
        status VARCHAR(25),
        description TEXT,
        project_id int(11),
        StartIP VARCHAR(45),
        EndIP VARCHAR(45),
        RVID VARCHAR(255),
        isNew VARCHAR(10),
        score INT(4),
        country_id INT(11),
        old_id varchar(255),
        old_id_date DATETIME,
        domain VARCHAR(255),
        fraud_profile_score INT(10),
        IsMobile tinyint,
        FPF1 tinyint,
        FPF2 tinyint,
        FPF3 tinyint,
        FPF4 tinyint,
        FPF5 tinyint,
        FPF6 tinyint,
        created_datetime DATETIME,
        PRIMARY KEY (blocked_redirect_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //panellist_graph
        $sql = 'CREATE TABLE  IF NOT EXISTS {{panellist_graph}} (
	  panellist_graph_id INT(11) AUTO_INCREMENT
	, project_id INT(11)
	, vendor_project_id INT(11)
	, time_consumed INT(11)
	, total_redirected INT(11) NOT NULL DEFAULT 0
	, total_disqualified INT(11) NOT NULL DEFAULT 0
	, total_completed INT(11) NOT NULL DEFAULT 0
	, total_quota_full INT(11) NOT NULL DEFAULT 0
        , PRIMARY KEY (panellist_graph_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //Email table structure's added by trk
        //Email Body Template Master
        $sql = 'CREATE TABLE IF NOT EXISTS {{template_email_subjects}} (
            `email_subjectid` int(11) NOT NULL auto_increment PRIMARY KEY,
            `subject_text` varchar(500) NOT NULL ,
            created_datetime DATETIME,
            updated_datetime DATETIME,
            IsActive tinyint NOT NULL DEFAULT 1
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //Email Subject Template Master
        $sql = 'CREATE TABLE IF NOT EXISTS {{template_email_body}} (
            email_bodyid int(11) NOT NULL auto_increment PRIMARY KEY,
            content_text text NOT NULL ,
            created_datetime DATETIME,
            updated_datetime DATETIME,
            IsActive tinyint NOT NULL DEFAULT 1
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //Email Template Master
        $sql = 'CREATE TABLE IF NOT EXISTS {{template_emails}} (
            `template_emailid` int(11) NOT NULL auto_increment PRIMARY KEY,
            `title_text` varchar(50) NOT NULL ,
            `use_in` int(2) NOT NULL ,
            `email_subjectid` int(11) NOT NULL ,
            `email_bodyid` int(11) NOT NULL , 
            created_datetime DATETIME,
            updated_datetime DATETIME,
            IsActive tinyint NOT NULL DEFAULT 1
         ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //Email Subject Translation Master
        $sql = 'CREATE TABLE IF NOT EXISTS {{translation_email_subjects}} (
            `translation_emailsubid` int(11) NOT NULL auto_increment PRIMARY KEY,
            `email_subjectid` int(11) NOT NULL ,
            language_code_dest VARCHAR(10) not null,
            `translated_subject` varchar(500) NOT NULL ,
            created_datetime DATETIME,
            updated_datetime DATETIME,
            IsActive tinyint NOT NULL DEFAULT 1
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //Email Body Translation Master
        $sql = 'CREATE TABLE IF NOT EXISTS {{translation_email_body}} (
            `translation_emailbodyid` int(11) NOT NULL auto_increment PRIMARY KEY,
            `email_bodyid` int(11) NOT NULL ,
            `language_code_dest` VARCHAR(10) not null,
            `translated_body` TEXT NOT NULL ,
            created_datetime DATETIME,
            updated_datetime DATETIME,
            IsActive tinyint NOT NULL DEFAULT 1
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //messages
        $sql = 'CREATE TABLE  IF NOT EXISTS {{messages}} (
	  messages_id INT(11) AUTO_INCREMENT
	, type_id INT(11) NOT NULL DEFAULT 1
	, header VARCHAR(65) DEFAULT NULL
	, body TEXT
	, receipid INT(11) NOT NULL
	, senderid INT(11) NOT NULL DEFAULT 0
	, Importance INT(11) DEFAULT NULL
	, created DATETIME DEFAULT NULL
	, isRead INT(11) NOT NULL DEFAULT 0
	, companyid INT(11) DEFAULT NULL
	, customerid INT(11) DEFAULT NULL
	, chainid INT(11) DEFAULT NULL
        , PRIMARY KEY (messages_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();


        //activation_temp
        $sql = 'CREATE TABLE IF NOT EXISTS {{activation_temp}} (
                id BIGINT(11) NOT NULL AUTO_INCREMENT,
                panelllist_id BIGINT(11) NOT NULL,
                code VARCHAR(100) NOT NULL,
                email VARCHAR(150) NOT NULL,
                password VARCHAR(150) NOT NULL,
                activation_type VARCHAR(50) NOT NULL,
                IsActive TINYINT NOT NULL DEFAULT 1,
                PRIMARY KEY (id)
                ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //email_message
        $sql = 'CREATE TABLE IF NOT EXISTS {{email_message}} (
                id INT(11) NOT NULL AUTO_INCREMENT,
                email_id VARCHAR(100) NOT NULL,
                subject VARCHAR(50) NOT NULL,
                body TEXT NOT NULL,
                email_to INT(11) NOT NULL,
                email_from INT(11) NOT NULL,
                parent INT(11) NOT NULL DEFAULT 0,
                sender VARCHAR(1) NOT NULL,
                status VARCHAR(10) NOT NULL DEFAULT "Unread",
                created_datetime DATETIME,
                PRIMARY KEY (id)
                ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //nilesh start
        //profile_category
        $sql = 'CREATE TABLE IF NOT EXISTS {{profile_category}} (
        id INT(5) NOT NULL AUTO_INCREMENT
        , title varchar(100) NOT NULL
        , IsActive int(1) NOT NULL DEFAULT 1
        , sorder int(8) NOT NULL DEFAULT 0
        , user_id INT(8)
        , created_date DATETIME
        , modified_date DATETIME
        , PRIMARY KEY (id)
        ,INDEX profile_category_titlex (title)

        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //query_send_details
        $sql = 'create table IF NOT EXISTS {{query_send_details}} (
                id int(11) NOT NULL AUTO_INCREMENT,
                send_id	int(11) not null default 0,
                query_id int(11) not null default 0,
                project_id int(11) not null default 0,
                subjectt_id int(11) not null default 0,
                template_id int(11) not null default 0,
                panellist_id int(11) not null default 0,
                send tinyint(1) not null default 0,
                reminder tinyint(1) not null default 0,
                status tinyint(1) not null default 0,
                userid int (5) not null default 0,
                created_date datetime not null,
                send_date datetime not null,
                primary key (id),		
                unique index (project_id,panellist_id))
                ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //query_detail
        $sql = 'CREATE TABLE IF NOT EXISTS {{query_detail}} (
                  query_id INT(11) NOT NULL DEFAULT 0,
                  question_id INT(11) NOT NULL DEFAULT 0,
                  answer_id INT(11) NOT NULL DEFAULT 0
                )
                ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //panellist_project
        $sql = 'create table IF NOT EXISTS {{panellist_project}}(	
                id int(255) not null auto_increment,
                panellist_id int (100) not null default 0,
                project_id int (100) not null default 0,
                project_url TEXT,
                points int(11) not null default 0,
                status VARCHAR(50) not null,	
                created_date datetime not null	,
                taken_date datetime,
                primary key (id),		
                unique index (project_id,panellist_id))
                ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //profile question
        $sql = 'CREATE TABLE IF NOT EXISTS {{profile_question}} (
        id int(11) NOT NULL AUTO_INCREMENT
        ,category_id int(5) NOT NULL
        ,short_title varchar(100) NOT NULL
        ,title text not null
        ,field_type varchar(50) not null
        ,is_other int(1) not null default 0
        ,is_other_field_type varchar(50) not null
        ,outdate_threshold varchar(3) not null
        ,priority int(5)
        ,is_profile int(1) not null default 0
        ,is_project int(1) not null default 0
        ,IsActive int(1) not null default 1
        ,sorder int(8) not null default 0
        ,user_id int(8)
        ,created_date datetime
        ,modified_date datetime
        , PRIMARY KEY (id)
        ,INDEX profile_category_stitlex (short_title)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //profile answer
        $sql = 'CREATE TABLE IF NOT EXISTS {{profile_answer}} (
        id int(11) not null AUTO_INCREMENT
        ,question_id int(11) not null
        ,category_id int(5) not null
        ,title varchar(200) not null
        ,IsActive int(1) not null default 1
        ,sorder int(8) not null default 0
        ,country_id int(11) not null default 0
        ,user_id int(8)
        ,created_date datetime not null
        ,modified_date datetime not null
        , PRIMARY KEY (id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //profile_question_type
        $sql = 'create table if not exists {{profile_question_type}} (
        id int(5) not null auto_increment
        ,`name` varchar(15) not null
        ,display_name varchar(25) not null
        ,for_other int(1) not null default 0
        ,PRIMARY KEY (id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //panellist_answer
        $sql = 'create table if not exists {{panellist_answer}} (
        `panellist_id` int(255) not null
        , status char(1) not null default "E"
        , is_fraud tinyint(1) not null default 0
        ,PRIMARY KEY (panellist_id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = 'create table if not exists {{reward_type}} (
        `id` int(11) NOT NULL AUTO_INCREMENT
        ,`name` varchar(30)
        ,display_name varchar(765)
        ,PRIMARY KEY (id)
        ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = 'CREATE TABLE IF NOT EXISTS {{reward_master}} (
        id int(11) not null AUTO_INCREMENT
        ,title varchar(600)
        ,short_title varchar(30)
        ,type int(11)
        ,image varchar(150)
        ,points double
        ,amount double
        ,expiration_date datetime
        ,sorder double
        ,user_id int(11)
        ,created_date datetime
        ,modified_date datetime
        ,IsActive tinyint NOT NULL DEFAULT 1
        , PRIMARY KEY (id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = 'CREATE TABLE IF NOT EXISTS {{cron_jobs}} (
          cron_id         int(11)  NOT NULL AUTO_INCREMENT
	, cron_command	VARCHAR(1000)
	, frequency 	VARCHAR(50) 
	, occur_day	VARCHAR(25) 
	, occur_time	VARCHAR(10) 
        , IsActive tinyint NOT NULL DEFAULT 1
        , LastExecutedOn datetime 
        , LastExecutionRemark   text
        , PRIMARY KEY (cron_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = "CREATE TABLE IF NOT EXISTS {{query_master}} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
        `qstring` text COLLATE utf8_unicode_ci,
        `project_id` int(11) NOT NULL DEFAULT '0',
        `zip` text COLLATE utf8_unicode_ci,
        `country` int(3) NOT NULL DEFAULT '0',
        `total_panellists` int(11) NOT NULL DEFAULT '0',
        `user_id` int(8) NOT NULL,
        `created_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `age` varchar(100) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = "CREATE TABLE IF NOT EXISTS {{client_code}} (
              `id` INT(255) NOT NULL AUTO_INCREMENT,
              `project_id` INT(100) NOT NULL DEFAULT '0',
              `panellist_redirect_id` INT(100) NOT NULL DEFAULT '0',
              `code` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` VARCHAR(10) COLLATE utf8_unicode_ci DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `project_id` (`project_id`,`code`),
              INDEX (id, code)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $result = Yii::app()->db->createCommand($sql)->query();
        //end nilesh

        $sql = 'CREATE TABLE IF NOT EXISTS {{rectify_redirects}} (
        rectify_id INT(11) NOT NULL AUTO_INCREMENT,
        project_id INT(11),
        rectify_type VARCHAR(1),
        rectify_no INT(11) DEFAULT 0,
        rectify_date DATE,
        PRIMARY KEY (rectify_id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = 'CREATE TABLE IF NOT EXISTS {{reward_request}} (
        id INT(11) NOT NULL AUTO_INCREMENT,
        reward_id INT(11) NOT NULL,
        panellist_id INT(11) NOT NULL,
        points INT(11) NOT NULL,
        amount DECIMAL(7,2) NOT NULL,
        paypal_trnaid VARCHAR(200) NOT NULL,
        date DATETIME NOT NULL,
        status INT(2) NOT NULL,
        completed_date DATETIME NOT NULL,
        PRIMARY KEY (id)
        ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();

        //18/06/2014 Add BY Hari
        $sql = 'CREATE TABLE IF NOT EXISTS {{CronLog}} (
            CronLogID INT(11) NOT NULL AUTO_INCREMENT,
            Start_DateTime DATETIME NOT NULL,
            End_DateTime DATETIME NOT NULL,
            PRIMARY KEY (CronLogID)
            ) ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = Yii::app()->db->createCommand($sql)->query();
        //18/06/2014 End
    }

    function InsertDefaultValue($status_name, $status_order, $status_for, $status_color='') {
        $instsql = "INSERT INTO {{project_status_master}} (status_name,status_order,status_for,status_color)
                VALUES('$status_name', '$status_order', '$status_for','$status_color');";
        $result = Yii::app()->db->createCommand($instsql)->query();
    }

//End CreateTables
//Start Insert
    function InsertValue() {
        $sql = "TRUNCATE {{project_status_master}}";
        $result = Yii::app()->db->createCommand($sql)->query();

        $sql = "SELECT COUNT(*) AS cnt FROM {{project_status_master}}";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if ($result['cnt'] == 0) {
// Project Status
            $this->InsertDefaultValue('testing', '1', 'p', 'blue');
            $this->InsertDefaultValue('running', '2', 'p', 'green');
            $this->InsertDefaultValue('hold', '3', 'p', 'orange');
            $this->InsertDefaultValue('completed', '4', 'p', 'yellow');
            $this->InsertDefaultValue('closed', '5', 'p', 'red');

// Redirection Status
            $this->InsertDefaultValue('Completed', '101', 'r');
            $this->InsertDefaultValue('Disqualified', '102', 'r');
            $this->InsertDefaultValue('Quota FULL', '103', 'r');
            $this->InsertDefaultValue('Redirected', '104', 'r');
            $this->InsertDefaultValue('Rejected - Failed SECURITY', '105', 'r');
            $this->InsertDefaultValue('Rejected - Inconsistency', '106', 'r');
            $this->InsertDefaultValue('Rejected - Poor OPEN ENDS', '107', 'r');
            $this->InsertDefaultValue('Rejected - Quality issues', '108', 'r');
            $this->InsertDefaultValue('Rejected - Speeding', '109', 'r');
        }

//add nilesh
//profile_category
        $sql = "SELECT COUNT(*) AS cnt FROM {{profile_category}}";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if ($result['cnt'] == 0) {
            $instsql = "insert into {{profile_category}} (`id`, `title`, `IsActive`, `sorder`, `user_id`, `created_date`, `modified_date`) 
                values('1','Registration','1','0','1','2014-04-24 09:08:31','2014-04-24 09:08:31'),
                ('2','Geogrophical','1','0','1','2014-04-24 10:23:52','2014-04-24 10:55:24'),
                ('3','Profile','1','0','1','2014-04-24 10:55:38','2014-04-24 10:55:38'),
                ('4','Project','1','0','1','2014-04-24 10:55:55','2014-04-24 10:55:55')";
            $result = Yii::app()->db->createCommand($instsql)->query();
        }

//profile_question_type
        $sql = "SELECT COUNT(*) AS cnt FROM {{profile_question_type}}";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if ($result['cnt'] == 0) {
            $sql = "insert into {{profile_question_type}} (`id`, `name`, `display_name`, `for_other`) values('1','Text','Text','0'),
                ('2','TextArea','TextArea','0'),
                ('3','CheckBox','CheckBox','0'),
                ('4','Radio','Radio','0'),
                ('5','DropDown','DropDown','0'),
                ('6','CheckBox','Addition Value (Check Box)','1'),
                ('7','Radio','Single Choice (Radio Butt)','1'),
                ('8','DOB','Date Of Birth','0')";
            $result = Yii::app()->db->createCommand($sql)->query();
        }


        $sql = "SELECT COUNT(*) AS cnt FROM {{reward_type}}";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if ($result['cnt'] == 0) {
            $sql = "insert into {{reward_type}}
                (`id`, `name`, `display_name`)
                values('1','PayPal','PayPal'),
                ('2','other','other')";
            $result = Yii::app()->db->createCommand($sql)->query();
        }
//end nilesh
    }

//End Insert
// Start CreateViews
    function CreateViews() {
        $sql = "SELECT cm.*,sm.state_Name, zm.zone_Name, com.country_name,com.continent
              FROM {{city_master}} cm
              left outer join {{state_master}} sm on cm.state_id=sm.state_id
              left outer join {{zone_master}} zm on sm.zone_id=zm.zone_id
              left outer join {{country_master}} com on zm.country_id=zm.country_id 
              order by com.country_name,zm.zone_Name,sm.state_Name,cm.city_Name
              ";
        $this->DropNCreateView('regions', $sql);

        $sql = "SELECT cm.* ,CONCAT_WS(' ',first_name,middle_name,last_name) AS full_name,GROUP_CONCAT(ct.contact_title_id) AS contact_title_id
                ,GROUP_CONCAT(ctym.contact_title_name) AS contact_title_name,cgm.contact_group_name 
                FROM {{contact_master}} cm
                LEFT OUTER JOIN {{map_contact_n_titles}} ct ON ct.contact_id = cm.contact_id
                LEFT OUTER JOIN {{contact_group_master}} cgm ON cgm.contact_group_id = cm.contact_group_id
                LEFT OUTER JOIN {{contact_title_master}} ctym ON ctym.contact_title_id = ct.contact_title_id
                WHERE cm.contact_type_id = '2' GROUP BY ct.contact_id";
        $this->DropNCreateView('contacts', $sql);

        $sql = "SELECT cm.* ,GROUP_CONCAT(ct.company_type_id) AS company_type_id
                ,GROUP_CONCAT(ctym.company_type) AS company_type 
                ,GROUP_CONCAT(ctym.company_type_name) AS company_type_name,cgm.contact_group_name 
                FROM {{contact_master}} cm
                LEFT OUTER JOIN {{map_company_n_types}} ct ON ct.company_id = cm.contact_id
                LEFT OUTER JOIN {{contact_group_master}} cgm ON cgm.contact_group_id = cm.contact_group_id
                LEFT OUTER JOIN {{company_type_master}} ctym ON ctym.company_type_id = ct.company_type_id
                WHERE cm.contact_type_id = '1' GROUP BY ct.company_id";
        $this->DropNCreateView('company', $sql);

        $sql = "SELECT pm.*, cli.company_name AS client_name
                , CONCAT_WS(' ',con.first_name,con.middle_name,con.last_name) AS contact_name
                , mgr.full_name AS manager_name, sal.full_name AS sales_name, cm.country_name,ps.status_name,ps.status_color
                FROM {{project_master}} pm
                LEFT OUTER JOIN {{contact_master}} cli ON pm.client_id=cli.contact_id
                LEFT OUTER JOIN {{contact_master}} con ON pm.contact_id=con.contact_id
                LEFT OUTER JOIN {{users}} mgr ON pm.manager_user_id=mgr.uid
                LEFT OUTER JOIN {{users}} sal ON pm.sales_user_id=sal.uid
                LEFT OUTER JOIN {{country_master}} cm  ON pm.country_id=cm.country_id
                LEFT OUTER JOIN {{project_status_master}} ps ON ps.status_id = pm.project_status_id";
        $this->DropNCreateView('project_master', $sql);

        $sql = "SELECT pmv.* ,pm.project_name,pm.friendly_name,pm.parent_project_id,pm.client_id
                ,pm.client_proj_num,pm.client_ord_num,pm.contact_id,pm.manager_user_id,pm.sales_user_id
                ,pm.country_id,pm.required_completes AS proj_required_completes,pm.QuotaBuffer_Completes AS proj_QuotaBuffer_Completes 
                ,pm.completes_validation AS proj_completes_validation ,pm.CPC AS proj_CPC,pm.IR AS proj_IR
                ,pm.expected_los AS proj_expected_los,pm.avg_los AS proj_avg_los,pm.total_los AS total_los,pm.reward_points AS proj_reward_points ,pm.client_link
                ,pm.total_redirected AS proj_total_redirected
                ,pm.total_completed AS proj_total_completed,pm.total_quota_full AS proj_total_quota_full,pm.total_disqualify AS proj_total_disqualify
                ,pm.total_rejected AS proj_total_rejected,pm.extra_completes AS proj_extra_completes,pm.total_errors AS proj_total_errors
                ,pm.notes AS proj_notes,pm.project_status_id,pm.RIDCheck,psm.status_name,cm.company_name, usr.full_name as sales_name
                FROM {{project_master_vendors}} pmv
                LEFT OUTER JOIN {{project_master}} pm ON pmv.project_id=pm.project_id
                LEFT OUTER JOIN {{project_status_master}} psm ON psm.status_id = pmv.vendor_status_id
                LEFT OUTER JOIN {{contact_master}} cm  ON cm.contact_id = pmv.vendor_id
                LEFT OUTER JOIN {{users}} AS usr ON pm.sales_user_id=usr.uid";
        $this->DropNCreateView('project_master_vendors', $sql);

        $sql = 'SELECT es.email_subjectid,es.subject_text,tr_es.translation_emailsubid,tr_es.language_code_dest,tr_es.translated_subject,es.isactive
                FROM {{template_email_subjects}} es
                LEFT JOIN {{translation_email_subjects}} tr_es ON es.email_subjectid = tr_es.email_subjectid';
        $this->DropNCreateView('email_subjects', $sql);

        $sql = 'SELECT et.*,eb.content_text,es.subject_text 
                FROM {{template_emails}} et
                LEFT JOIN {{template_email_subjects}} es ON es.email_subjectid = et.email_subjectid
                LEFT JOIN {{template_email_body}} eb ON eb.email_bodyid = et.email_bodyid';
        $this->DropNCreateView('email_template', $sql);

        $sql = "SELECT pm.*,CONCAT_WS(' ',first_name,last_name) AS full_name 
                , CASE status
                    when 'E' then 'Enabled'
                    when 'D' then 'Disabled'
                    when 'C' then 'Cancelled'
                    when 'R' then 'Registered'
                END as status_name
                FROM {{panel_list_master pm}} ";
        $this->DropNCreateView('panel_list_master', $sql);

        $sql = "SELECT 1 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='HOURLY' AND IsActive=1 
                UNION ALL
                SELECT 2 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='DAILY' AND IsActive=1 
                UNION ALL
                SELECT 3 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='WEEKLY' AND IsActive=1 
                UNION ALL
                SELECT 4 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='MONTHLY' AND IsActive=1 
                UNION ALL
                SELECT 5 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='ONCE' AND IsActive=1 
                UNION ALL
                SELECT 6 AS ORD, a.* FROM {{cron_jobs}} AS a WHERE frequency='MINUTE' AND IsActive=1 
                ORDER BY occur_day,occur_time";
        $this->DropNCreateView('cron_jobs', $sql);


        $sql = "select 'Send' as type, qsd.*,pm.reward_points as points from 		
                {{query_send_details}} as qsd 		
                left join {{project_master}} as pm on pm.project_id = qsd.project_id		
                where qsd.status= 0 and qsd.send =1 and qsd.reminder = 0		
                UNION ALL		
                select 'Resend' as `type`, qsd.*, IFNULL(pp.points,0) as points from 		
                {{query_send_details}} as qsd left join {{panellist_project}} as pp on  pp.project_id = qsd.project_id and pp.panellist_id = qsd.panellist_id		
                where qsd.status= 0 and qsd.send =1 and qsd.reminder = 1";
        $this->DropNCreateView('sendingqueque', $sql);

        $sql = "SELECT plr.panellist_redirect_id,plr.redirect_status_id,plr.prev_redirect_status_id
                , rrh.project_id, rrh.rectify_type,rrh.rectify_no, rrh.rectify_date
                , pm.project_name,pm.friendly_name, pm.status_name AS project_status_name
                , psm.status_name AS redirect_status_name, psm.status_color AS redirect_status_color
                , psm.status_name AS prev_redirect_status_name, psm.status_color AS prev_redirect_status_color
                FROM {{panellist_redirects}} plr
                LEFT OUTER JOIN {{rectify_redirects}} rrh ON rrh.rectify_id=plr.rectify_id
                LEFT OUTER JOIN {{view_project_master}} pm ON rrh.project_id=pm.project_id
                LEFT OUTER JOIN {{project_status_master}} psm ON psm.status_id=plr.redirect_status_id
                LEFT OUTER JOIN {{project_status_master}} psmp ON psmp.status_id=plr.prev_redirect_status_id
                WHERE IFNULL(plr.rectify_id,0)>0
                ORDER BY rrh.rectify_id,panellist_redirect_id";
        $this->DropNCreateView('rectify_redirects', $sql);

        $sql = "SELECT plr.*, pmv.vendor_status_id
                , pmv.CPC ven_CPC, pmv.required_completes ven_required_completes, pmv.QuotaBuffer_Completes ven_quotaBuffer_completes
                , pmv.max_redirects AS ven_max_redirects, pmv.total_redirected AS ven_total_redirected, pmv.total_completed AS ven_total_completed, pmv.total_quota_full AS ven_total_quota_full
                , pmv.total_disqualified AS ven_total_disqualified, pmv.total_rejected AS ven_total_rejected, pmv.extra_completed AS ven_extra_completed
                , vcm.company_name, ccm.full_name vendor_fullname, vpm.full_name AS panellist_fullname
                , pm.project_name, pm.status_name AS project_status
                , psm.status_name AS redirect_status_name, psmr.status_name AS prev_redirect_name
                FROM {{panellist_redirects}} plr
                LEFT OUTER JOIN {{view_project_master}} pm ON plr.project_id=pm.project_id
                LEFT OUTER JOIN {{project_master_vendors}} pmv ON plr.vendor_project_id=pmv.vendor_project_id
                LEFT OUTER JOIN {{view_company}} vcm ON plr.client_id=vcm.contact_id
                LEFT OUTER JOIN {{view_contacts}} ccm ON plr.vendor_id=ccm.contact_id
                LEFT OUTER JOIN {{view_panel_list_master}} vpm ON plr.panellist_id=vpm.panel_list_id
                LEFT OUTER JOIN {{project_status_master}} psm ON plr.redirect_status_id=psm.status_id
                LEFT OUTER JOIN {{project_status_master}} psmr ON plr.prev_redirect_status_id=psmr.status_id";
        $this->DropNCreateView('panellist_redirects', $sql);


        $sql = "SELECT 'Parent' AS type,id AS msgid,pr.id AS act_pr_id,pr.email_id,pr.subject,pr.body,pr.email_to,pr.email_from,pr.parent,pr.sender,pr.status,pr.created_datetime 
                , (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=pr.parent) AS parent_status
                FROM {{email_message}} pr WHERE id IN (SELECT parent FROM {{email_message}}) OR parent = 0
                UNION ALL
                SELECT 'Childs' AS type,id AS msgid, pr.parent,CONCAT(' - > ',pr.email_id),CONCAT(SPACE(2),pr.subject),CONCAT(SPACE(5),pr.body),pr.email_to,pr.email_from,pr.parent,pr.sender,pr.status,pr.created_datetime 
                , (SELECT MAX(STATUS) FROM {{email_message}} WHERE parent=pr.parent) AS parent_status
                FROM {{email_message}} pr 
                WHERE parent!=0
                ORDER BY act_pr_id,msgid";
        $this->DropNCreateView('email_message', $sql);


        $sql = "SELECT 'Parent' AS TYPE,pr.id AS msgid,pr.id AS act_pr_id,                (
                SELECT IFNULL(MAX(STATUS),pr.status) FROM {{email_message}} WHERE (parent = pr.id)) AS child_status
                FROM {{email_message}} pr
                WHERE (id IN(SELECT parent FROM {{email_message}}) OR (`pr`.`parent` = 0))";
        $this->DropNCreateView('parent_emails', $sql);


        $sql = "SELECT 'Parent' AS type,pr.id AS ordid, pr.*
                , (SELECT IFNULL(MAX(STATUS),pr.status) FROM {{email_message}} WHERE parent=pr.id) AS actual_status
                FROM {{email_message}} pr 
                WHERE (pr.id IN(SELECT parent FROM {{email_message}}) OR (pr.parent = 0))
                UNION ALL 
                SELECT 'Child' AS TYPE,pr.parent AS ordid, pr.*, pr.status AS actual_status
                FROM {{email_message}} pr 
                WHERE pr.id NOT IN (SELECT msgid FROM {{view_parent_emails}})";
        $this->DropNCreateView('unread_emails_all', $sql);

        $sql = "SELECT x.* FROM {{view_unread_emails_all}} x WHERE actual_status ='Unread' ORDER BY ordid,id";
        $this->DropNCreateView('unread_emails', $sql);


        $sql = "SELECT 'Parent' AS type,pr.id AS ordid, pr.*
                , (SELECT IFNULL(MIN(STATUS),pr.status) FROM {{email_message}} WHERE parent=pr.id) AS actual_status
                FROM {{email_message}} pr 
                WHERE (pr.id IN(SELECT parent FROM {{email_message}}) OR (pr.parent = 0))
                UNION ALL 
                SELECT 'Child' AS TYPE,pr.parent AS ordid, pr.*, pr.status AS actual_status
                FROM {{email_message}} pr 
                WHERE pr.id NOT IN (SELECT msgid FROM {{view_parent_emails}})";
        $this->DropNCreateView('read_emails_all', $sql);

        $sql = "SELECT x.* FROM {{view_read_emails_all}} x WHERE actual_status ='Read' ORDER BY ordid,id";
        $this->DropNCreateView('read_emails', $sql);


        $sql = "SELECT rq.id,pm.full_name,pm.email,rq.date,rt.name,rm.title,rq.amount,rq.status,rq.paypal_trnaid
                FROM {{reward_request}} rq
                LEFT JOIN {{view_panel_list_master}} pm ON pm.panel_list_id=rq.panellist_id
                LEFT JOIN {{reward_master}} rm ON rm.id=rq.reward_id
                LEFT JOIN {{reward_type}} rt ON rt.id = rm.type order by date desc";
        $this->DropNCreateView('reward_request', $sql);
    }

// end CreateViews
// Start CreateSPs
    function CreateSPs() {
//Start SP
        $sql = 'DROP PROCEDURE IF EXISTS CreateIndex;
        CREATE PROCEDURE CreateIndex
        (
            mdatabase VARCHAR(64),
            mtable    VARCHAR(64),`
            mindex    VARCHAR(64),
            mcolumns  VARCHAR(64)
        )
        BEGIN
            DECLARE IndexIsThere INTEGER;
            SELECT COUNT(1) INTO IndexIsThere
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE table_schema = mdatabase
            AND   table_name   = mtable
            AND   index_name   = mindex;
                /* check and drop index */
            IF IndexIsThere > 0 THEN
                SET @sqlstmt = CONCAT("DROP INDEX ",mindex," ON ",mtable);
                PREPARE st FROM @sqlstmt;
                EXECUTE st;
                DEALLOCATE PREPARE st;
            END IF;

            /* create index */
                SET @sqlstmt2 = CONCAT("CREATE INDEX ",mindex," ON ",mdatabase,".",mtable," (",mcolumns,")");
                PREPARE st2 FROM @sqlstmt2;
                EXECUTE st2;
                DEALLOCATE PREPARE st2;
        END';
        $result = Yii::app()->db->createCommand($sql)->query();
//End SP
    }

//Start CreateIndexes
    function CreateIndexes() {
        $dbname = explode('=', Yii::app()->db->connectionString);

//country_master
//$sql = 'CREATE UNIQUE INDEX {{country_master__countryname__uq}} ON {{country_master}}(country_name)';
        $sql = "CALL CreateIndex('$dbname[2]','{{country_master}}','{{country_master__countryname__uq}}','(country_name)')";
//zone_master
        $sql = "CALL CreateIndex('$dbname[2]','{{zone_master}}','{{zone_master__countryid_name__uq}}','(country_id,zone_name)')";

//state_master
        $sql = "CALL CreateIndex('$dbname[2]','{{state_master}}','{{state_master__zoneid_name__uq}}','(zone_id,state_name)')";

//city_master
        $sql = "CALL CreateIndex('$dbname[2]','{{city_master}}','{{city_master__stateid_name__uq}}','(state_id,city_name)')";

//company_type_master
        $sql = "CALL CreateIndex('$dbname[2]','{{company_type_master}}','{{company_type_master__name__uq}}','(company_type_name)')";

//contact_group_master
        $sql = "CALL CreateIndex('$dbname[2]','{{contact_group_master}}','{{contact_group_master__name__uq}}','(contact_group_name)')";

//contact_master
        $sql = "CALL CreateIndex('$dbname[2]','{{contact_master}}','{{contact_master__names__uq}}','(first_name,middle_name,last_name)')";

//map_company_n_types
        $sql = "CALL CreateIndex('$dbname[2]','{{map_company_n_types}}','{{map_company_n_types__types__uq}}','(company_id,company_type_id)')";
        $sql = "CALL CreateIndex('$dbname[2]','{{map_company_n_types}}','{{map_company_n_types__titles__uq}}','(company_id,contact_title_id)')";

//cms_page_master
        $sql = "CALL CreateIndex('$dbname[2]','{{cms_page_master}}','{{cms_page_master__name_title__uq}}','(page_name,page_title)')";

//cms_page_content
        $sql = "CALL CreateIndex('$dbname[2]','{{cms_page_content}}','{{cms_page_content__pageid_languagecode__uq}}','(page_id,language_code)')";
    }

    function DropTable($table_name) {
        $result = Yii::app()->db->createCommand('DROP TABLE IF EXISTS {{' . $table_name . '}}')->query();
    }

//reset database strucure
    function resetDB() {
        $this->DropTable('city_master');
        $this->DropTable('state_master');
        $this->DropTable('zone_master');
        $this->DropTable('country_master');
        $this->DropTable('company_type_master');
        $this->DropTable('contact_group_master');
        $this->DropTable('contact_title_master');
        $this->DropTable('contact_master');
        $this->DropTable('map_company_n_types');
        $this->DropTable('map_contact_n_titles');
        $this->DropTable('panel_list_master');
        $this->DropTable('cms_page_master');
        $this->DropTable('cms_page_content');
        $this->DropTable('project_master');
        $this->DropTable('project_status_master');
        $this->DropTable('project_master_vendors');
        $this->DropTable('panellist_redirects');
        $this->DropTable('relevant_redirects');
        $this->DropTable('panellist_graph');
        $this->DropTable('template_email_subjects');
        $this->DropTable('template_email_body');
        $this->DropTable('template_emails');
        $this->DropTable('blocked_redirects');
        $this->DropTable('translation_email_subjects');
        $this->DropTable('translation_email_body');
        $this->DropTable('messages');
        $this->DropTable('profile_category');
        $this->DropTable('profile_question');
        $this->DropTable('profile_answer');
        $this->DropTable('profile_question_type');
        $this->DropTable('panellist_answer');
    }

}

//class
?>
