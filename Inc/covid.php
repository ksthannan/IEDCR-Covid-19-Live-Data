<?php 
namespace Iedcr\Covid;

class getIedcrData{

public function __construct(){

    add_action( 'wp_enqueue_scripts', [$this, 'iedcr_covid_scripts'] );
    
}

public function iedcr_covid_scripts(){
    wp_enqueue_style( 'iedcr_covid_style', IEDCR_COVID_ASSETS . '/style.css', array(), IEDCR_COVID_VERSION, 'all' );
    wp_enqueue_script( 'iedcr_covid_script', IEDCR_COVID_ASSETS . '/script.js', array('jquery'), IEDCR_COVID_VERSION,  true);
}

public function iedcr_data( $atts, $content = null ){

    $attr = shortcode_atts( array(
        'area' => 'full',
    ), $atts );
    

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://iedcr.gov.bd/');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'request=covid-19-bd');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $error = curl_error($ch);
    
    if($error){
        return 'Something wrong!';
    }else{
    
        $result = curl_exec($ch);
        $data_start = strpos($result,'<section id="innermid" class="grid-block">');
        $data_end = strpos($result,'</section>', $data_start);
    
        // Trimed data
        $trimed_data = substr($result, $data_start, ($data_end - $data_start));
    
        // Total tests
        $total_test_start = strpos($trimed_data,'<p>Total COVID-19 Tests');
        $total_test_end = strpos($trimed_data,'</div>', $total_test_start);
        $total_tests = substr($trimed_data, $total_test_start, ($total_test_end - $total_test_start));
    
        // 24 hours tests
        $today_test_start = strpos($trimed_data,'<p>Last 24 Hours Tests');
        $today_test_end = strpos($trimed_data,'</div>', $today_test_start);
        $today_tests = substr($trimed_data, $today_test_start, ($today_test_end - $today_test_start));
    
        // Positive cases
        $postive_cases_start = strpos($trimed_data,'<p>COVID-19 Positive Cases');
        $positive_cases_end = strpos($trimed_data,'</div>', $postive_cases_start);
        $positive_cases = substr($trimed_data, $postive_cases_start, ($positive_cases_end - $postive_cases_start));
    
        // Cases in Last 24 Hours
        $cases_last_day_start = strpos($trimed_data,'<p>Cases in Last 24 Hours');
        $cases_last_day_end = strpos($trimed_data,'</div>', $cases_last_day_start);
        $case_last_day = substr($trimed_data, $cases_last_day_start, ($cases_last_day_end - $cases_last_day_start));
    
        // Recovered
        $recovered_start = strpos($trimed_data,'<p>Recovered');
        $recovered_end = strpos($trimed_data,'</div>', $recovered_start);
        $recovered = substr($trimed_data, $recovered_start, ($recovered_end - $recovered_start));
    
        // Death Cases
        $death_start = strpos($trimed_data,'<p>Death Cases');
        $death_end = strpos($trimed_data,'</div>', $death_start);
        $death_cases = substr($trimed_data, $death_start, ($death_end - $death_start));
    
        if($attr['area'] == 'widget'){
            $area_class = 'widget_area';
        }else{
            $area_class = 'fullwidth_area';
        }

        $show_data = '<div class="iedcr_data_wrapper ' . $area_class . '">';
        
        $show_data .= '<h2>' . __('COVID-19 Status Bangladesh', 'iedcr-covid') . '</h2>';

        $show_data .= '<div class="total_tests iedcr_item">';
        $show_data .= $total_tests;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '<div class="today_tests iedcr_item">';
        $show_data .= $today_tests;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '<div class="positive_cases iedcr_item">';
        $show_data .= $positive_cases;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '<div class="last_day_cases iedcr_item">';
        $show_data .= $case_last_day;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '<div class="recovered_cases iedcr_item">';
        $show_data .= $recovered;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '<div class="death_cases iedcr_item">';
        $show_data .= $death_cases;
        $show_data .= '</div>';
        $show_data .= '</div>';
    
        $show_data .= '</div>';
    
        return $show_data;
    }
    
    
}

function iedcr_short_code(){
    add_shortcode('iedcr_live_data', [$this, 'iedcr_data']);
}

}


$covid_shortcode = new getIedcrData();
$covid_shortcode -> iedcr_short_code();
