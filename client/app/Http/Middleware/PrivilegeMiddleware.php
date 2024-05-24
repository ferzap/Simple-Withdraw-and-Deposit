<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;

class PrivilegeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $permission = $this->checkPrivilge();
        if($permission){
            return $next($request);
        } else {
            return redirect('/dashboard')->with("unauthorized","You're not authorized for that page!");
        }
    }

    public function checkPrivilge(){
        $validPermission = false;
        $allowedUrl = array(
            'dashboard',
            'profile',
            'profile/update',
            'profile/update-password',
            'logout'
        );

        if(Auth::check()){
            if(session()->has('administrator_logged_in')){
                $privilege = $allowedUrl;
                if(session('privilege') != NULL){
                    $privilege = array_merge($privilege, session('privilege')['links']);
                }

                $totalSeg = FacadesRequest::segments();
                $prefix = FacadesRequest::segment(1);
                $mainSeg = FacadesRequest::segment(2);
                $actionSeg = FacadesRequest::segment(3);
                $action2Seg = FacadesRequest::segment(4);

                foreach ($privilege as $key => $value) {
                    # code...
                    $urlArr = explode('/', $value);

                    if(count($totalSeg) == 1){
                        if(count($urlArr) == 1){
                            $prefixUrl = $urlArr[0];
                            if($prefix === $prefixUrl){
                                $validPermission = true;
                                return $validPermission;
                                break;
                            }
                        }
                    }
                    if(count($totalSeg) == 2){
                        if(count($urlArr) == 2){
                            $mainUrl = $urlArr[1];
                            if($mainSeg === $mainUrl){
                                $validPermission = true;
                                return $validPermission;
                                break;
                            }
                        }
                    }
                    if(count($totalSeg) == 3){
                        if(count($urlArr) == 3){
                            $mainUrl = $urlArr[1];
                            if($mainSeg === $mainUrl){
                                $actionUrl = $urlArr[2];
                                if($actionSeg === $actionUrl){
                                    $validPermission = true;
                                    return $validPermission;
                                    break;
                                }
                            }
                        }
                    }

                    if(count($totalSeg) == 4){
                        if(count($urlArr) == 4){
                            $mainUrl = $urlArr[1];
                            if($mainSeg === $mainUrl){
                                $actionUrl = $urlArr[2];
                                if($actionSeg === $actionUrl){
                                    $action2Url = $urlArr[3];
                                    if($action2Seg == $action2Url){
                                        $validPermission = true;
                                        return $validPermission;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
