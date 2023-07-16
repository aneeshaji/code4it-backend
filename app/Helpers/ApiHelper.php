<?php
   
    /**
     * Success Response Method.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   function sendResponse($result, $message)
   {
        $response = [
           'success' => true,
           'message' => $message,
           'results' => $result,
        ];
   
        return response()->json($response, 200);
   }
   
   
    /**
     * Error Response Method.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   function sendError($error, $errorMessages = [], $code = 404)
   {
        $response = [
           'success' => false,
           'message' => $error,
        ];
   
        !empty($errorMessages) ? $response['data'] = $errorMessages : null;
   
        return response()->json($response, $code);
   }