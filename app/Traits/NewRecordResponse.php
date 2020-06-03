<?php
namespace  App\Traits;

trait NewRecordResponse{

    public function ajaxResponse($model,$request){
           try {
            $data = $model::create($request);
            $response = [
                'success' => true,
                'error' => false,
                'data' => $data,
                'message' => '',
            ];
        } catch(\Exception $e) {
            $response = [
                'success' => false,
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }
}
