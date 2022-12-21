<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //

    public function get_repo_list(Request $request)
    {

        $curl_url = 'https://api.github.com/search/repositories?q=stars:>99';

        $ch = curl_init($curl_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Awesome-Octocat-App'));

        $output = curl_exec($ch);

        curl_close($ch);

        $output = json_decode($output, 'r');
        return $output;
        if (!empty($output)) {
            return count($output['items']);
        } else {
            return response()->json(['Data' => []], 400);
        }
    }

    public function get_result(Request $request)
    {

        $curl_url = 'https://api.github.com/search/repositories?q=stars:>0';

        $ch = curl_init($curl_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Awesome-Octocat-App'));

        $output = curl_exec($ch);

        curl_close($ch);

        $output = json_decode($output, 'r');

        if (!empty($output)) {


            foreach ($output['items'] as $value) {
                $result = $this->get_contributors_filter($value['owner']['login'], $value['name']);
                if (count($result) > 0) {
                    foreach ($result as $k => $v) {
                        $contributor[] = array(
                            "login" => isset($v['login']) ? $v['login'] : '',
                            "id" => isset($v['id']) ? $v['id'] : '',
                            "contributions" => isset($v['contributions']) ? $v['contributions'] : '',
                        );
                    }
                } else {
                    $contributor = [];
                }

                $data[] = array(
                    "id" => $value['id'],
                    "name" => $value['name'],
                    "html_url" => $value['html_url'],
                    "contributor" => $contributor,
                    "created_at" => $value['created_at'],
                );
            }
            return $data;
        } else {
            return response()->json(['Data' => []], 400);
        }
    }

    public function get_contributors_filter($user, $repos)
    {

        $curl_url = 'https://api.github.com/repos/' . $user . '/' . $repos . '/contributors';

        $ch = curl_init($curl_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Awesome-Octocat-App'));

        $output = curl_exec($ch);

        curl_close($ch);

        $output = json_decode($output, 'r');

        if (!empty($output)) {
            return $output;
        } else {
            return response()->json(['Data' => []], 400);
        }
    }

    public function get_contributors_list(Request $request)
    {

        $user = $request->user;

        $repos = $request->repos;

        $curl_url = 'https://api.github.com/repos/' . $user . '/' . $repos . '/contributors';

        $ch = curl_init($curl_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Awesome-Octocat-App'));

        $output = curl_exec($ch);

        curl_close($ch);

        $output = json_decode($output);

        if (!empty($output)) {
            return $output;
        } else {
            return response()->json(['Data' => []], 400);
        }
    }
}
