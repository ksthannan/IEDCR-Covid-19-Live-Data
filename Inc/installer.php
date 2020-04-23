<?php 
namespace Iedcr\Covid;

class Installer
{
    public function run(){
        $this->add_version();
    }

    public function add_version(){
        $installed = get_option('iedcr_covid_installed');

        if(! $installed){
            update_option('iedcr_covid_installed', time());
        }

        update_option('iedcr_covid_version', IEDCR_COVID_VERSION);


    }

}
