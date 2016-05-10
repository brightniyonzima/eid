<?php 

trait HttpTrait
{

    public function http_get( $url_path, $url_params = [], $expected_status_code = 200, $err_msg = '')
    {
        $this->auto_login();

        $url = $this->http_build_url( $url_path , $url_params );
        $http_reply = $this->get( $url );

        $actual_status_code = $http_reply->response->status();
        $this->confirm_http_status_code( $expected_status_code, $actual_status_code, $url, $err_msg );

        return $http_reply;
    }


    public function auto_login()
    {
        $user = factory('EID\Models\User')->create();

        $this->seeInDatabase('users', $user->toArray() );

        $this->be( $user );

        return $this;
    }


    public function http_build_url($url_path, $url_params = [])
    {
        $url_parts = explode('?', $url_path);
        
        if( empty($url_parts[1]) ){
            $url_parts[1] = "";
        }

        $base_url = $url_parts[0];
        $old_url_params = $url_parts[1];
        $new_url_params = http_build_query( $url_params );

        return $base_url . "?" . trim($old_url_params) . "&" . $new_url_params;
    }

    // public function confirm_httpResponse_ok( $http_reply, $url, $url_path )
    // {

    //     $expected_status_code = 200;
    //     $this->confirm_http_status_code($http_reply, $url, $url_path, $expected_status_code);

    //     // $status_code = $http_reply->response->status();

    //     // $err_msg = "Failed to fetch this url: $url_path " .
    //     //             "(Got status code: $status_code)\n\n" .
    //     //             "[Full URL = $url]";

    //     // $this->assertTrue( $status_code == 200, $err_msg);
    // }

    // public function confirm_httpResponse_404( $http_reply, $url, $url_path )
    // {
    //     $expected_status_code = 404;
    //     $this->confirm_http_status_code($http_reply, $url, $url_path);

    //     // $status_code = $http_reply->response->status();

    //     // $err_msg = "Failed to fetch this url: $url_path " .
    //     //             "(Got status code: $status_code)\n\n" .
    //     //             "[Full URL = $url]";

    //     // $this->assertTrue( $status_code == 404, $err_msg);
    // }

    

    public function confirm_http_status_code( $expected_status_code, $actual_status_code, $url, $err_msg )
    {
        
        $url_path = $this->get_base_url( $url );

        $err_msg .= "\n".   "URL that was fetched: $url_path \n\n" .
                            "Got status code: $actual_status_code (Expected: $expected_status_code)\n\n" .
                            "[Full URL = $url]";

        $this->assertTrue( $expected_status_code == $actual_status_code, $err_msg);
    }

    public function get_base_url( $full_url )
    {
        $url_parts = explode('?', $full_url);
        return $url_parts[0];
    }

}