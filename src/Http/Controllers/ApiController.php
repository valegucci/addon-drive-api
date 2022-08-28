<?php

namespace Vale\Addons\Drive\Http\Controllers;

use Vale\Addons\Drive\Services\GoogleDrive;
use Google\Service\Exception as GoogleException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    public function moveToFolder(Request $request)
    {
        return $this->handleApiRequest(function () use ($request) {
            $body = $request->all();

            $validation = \Validator::make($body, [
                'folder_id' => 'required',
                'file_id' => 'required',
            ]);

            if (!$validation->passes()) {
                throw new ValidationException($validation);
            }

            $drive = new GoogleDrive();
            $parents = $drive->moveFileToFolder($body['file_id'], $body['folder_id']);

            return response()->json($parents, Response::HTTP_OK);
        });
    }

    /**
     * @param \Closure $closure
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    private function handleApiRequest(\Closure $closure)
    {
        try {
            return $closure();
        } catch (MethodNotAllowedException $methodNotAllowedException) {
            return response()->json([
                "status" => false,
                "code" => Response::HTTP_METHOD_NOT_ALLOWED,
                "exit_code" => 1,
                "messages" => ["Method Not Allowed!"],
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        } catch (ValidationException $validationException) {
            return response()->json([
                "status" => false,
                "code" => Response::HTTP_NOT_ACCEPTABLE,
                "exit_code" => 1,
                "messages" => $validationException->errors(),
            ], Response::HTTP_NOT_ACCEPTABLE);
        } catch (RequestException $requestException) {
            return response()->json([
                "status" => false,
                "code" => Response::HTTP_NOT_ACCEPTABLE,
                "exit_code" => 1,
                "messages" => [$requestException->getMessage()],
            ], Response::HTTP_NOT_ACCEPTABLE);
        } catch (GoogleException $exception) {
            $message = json_decode($exception->getMessage());
            return response()->json([
                "status" => false,
                "code" => $exception->getCode(),
                "exit_code" => 1,
                "messages" => [(!empty($message->error->message) ? $message->error->message : $message)],
            ], $exception->getCode());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
