<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\Airport;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\filters\AccessControl;


/**
 * AirportController implements the CRUD actions for Airport model.
 */
class AirportController extends Controller
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'only' => ['create','update'],
                'rules' =>  [
                    [
                        'allow' => true,
                         'roles' => ['@']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                     'index'=>['get'],
                     'view'=>['get'],
                     'create'=>['post'],
                     'update'=>['post'],
                     'delete' => ['delete'],
                     'deleteall'=>['post'],
                ],              
            ]
        ];
    }
    
   
    public function beforeAction($event)
    {
         $action = $event->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }
         $verb = Yii::$app->getRequest()->getMethod();
       
       $allowed = array_map('strtoupper', $verbs);
       
       if (!in_array($verb, $allowed)) {
            
             $this->setHeader(400);
             echo json_encode(array('status'=>0,'error_code'=>400,'message'=>'Method not allowed'),JSON_PRETTY_PRINT);
             exit;
            
         }  
         
       return true;  
    }
   
    /**
     * Lists all Airport models.
     * @return mixed
     */
    public function actionIndex()
    {
     
    

          $params=$_REQUEST;
          $filter=array();
          $sort="";
         
          $page=1;
          $limit=10;
             
           if(isset($params['page']))
             $page=$params['page'];
              
               
           if(isset($params['limit']))
              $limit=$params['limit'];
               
            $offset=$limit*($page-1);
             
             
            /* Filter elements */
           if(isset($params['filter']))
            {
             $filter=(array)json_decode($params['filter']);
            }
            
             if(isset($params['datefilter']))
            {
             $datefilter=(array)json_decode($params['datefilter']);
            }
            
             
            if(isset($params['sort']))
            {
              $sort=$params['sort'];
        		 if(isset($params['order']))
        		{  
        		    if($params['order']=="false")
        		     $sort.=" desc";
        		    else
        		     $sort.=" asc";
        		 
        		}
            }

                $query=new Query;

                 if(isset($params['airport_code']))
                {  
                     $query->offset($offset)
                                 ->limit($limit)
                                 ->from('airport')
                                 ->andFilterWhere(['like', 'airport_code', $params['airport_code']])
                                 ->orderBy($sort)
                                 ->select("id,airport_code,airport_name,country,city");
                 
                }else{

                    $query->offset($offset)
                                 ->limit($limit)
                                 ->from('airport')
                                 ->orderBy($sort)
                                 ->select("id,airport_code,airport_name,country,city");
                }

            /*    if(!empty($filter)){
                $query->offset($offset)
                                 ->limit($limit)
                                 ->from('airport')
                           //      ->andFilterWhere(['like', 'id', $filter['id']])
                                 ->andFilterWhere(['like', 'airport_code', $filter['airport_code']])
                             //    ->andFilterWhere(['like', 'airport_name', $filter['airport_name']])
                                 ->orderBy($sort)
                                 ->select("id,airport_code,airport_name,country,city");
                }else{ */
                    
               /* }*/
              	                  
               $command = $query->createCommand();
               $models = $command->queryAll();
               
               $totalItems=$query->count();
          
          $this->setHeader(200); 
         
          echo json_encode(array('status'=>1,'data'=>$models,'totalItems'=>$totalItems),JSON_PRETTY_PRINT);
       
    }
      

    /**
     * Displays a single Airport model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
   
      $model=$this->findModel($id);
      
      $this->setHeader(200);
      echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
	
    }

    /**
     * Creates a new Aiport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if( Yii::$app->user->can( 'Create-Airport' ) )
        {

         $params=$_REQUEST;
         $model = new Airport();
         $model->attributes=$params;

                if ($model->save()) {
                
                     $this->setHeader(200);
                     echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
                } 
                else
                {
                     $this->setHeader(400);
                     echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
                }

        }else{
                     $this->setHeader(403);
                     echo json_encode(array('status'=>0,'error_code'=>403,'errors'=>$this->_getStatusCodeMessage(403)),JSON_PRETTY_PRINT);
        }        
     
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if( Yii::$app->user->can( 'Update-Airport' ) )
        {
            $params=$_REQUEST;
    
            $model = $this->findModel($id);
        
            $model->attributes=$params;

            if ($model->save()) {
            
                 $this->setHeader(200);
                 echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
              
            } 
            else
            {
                 $this->setHeader(400);
                 echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
            }

        }else{

            $this->setHeader(403);
             echo json_encode(array('status'=>0,'error_code'=>403,'errors'=>$this->_getStatusCodeMessage(403)),JSON_PRETTY_PRINT);

        }
        
        
    }

    /**
     * Deletes an existing Aiport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if( Yii::$app->user->can( 'Delete-Airport' ) )
        {
            $model=$this->findModel($id);
        
        if($model->delete())
         { 
             $this->setHeader(200);
             echo json_encode(array('status'=>1,'data'=>array_filter($model->attributes)),JSON_PRETTY_PRINT);
         
         }
         else
         {
           
             $this->setHeader(400);
             echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
         }

        }else{

                    $this->setHeader(403);
                     echo json_encode(array('status'=>0,'error_code'=>403,'errors'=>$this->_getStatusCodeMessage(403)),JSON_PRETTY_PRINT);
        }

        

    }
    public function actionDeleteall()
    {
        if( Yii::$app->user->can( 'Delete-Airport' ) )
        {
            $ids=json_decode($_REQUEST['ids']);
      
            $data=array();
            
            foreach($ids as $id)
            {
              $model=$this->findModel($id);
              
              if($model->delete())
                $data[]=array_filter($model->attributes);
              else
               {
                 $this->setHeader(400);
                 echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>$model->errors),JSON_PRETTY_PRINT);
                 return;
               }  
            }
            
            $this->setHeader(200);
            echo json_encode(array('status'=>1,'data'=>$data),JSON_PRETTY_PRINT);

        }else{

            $this->setHeader(403);
            echo json_encode(array('status'=>0,'error_code'=>403,'errors'=>$this->_getStatusCodeMessage(403)),JSON_PRETTY_PRINT);

        }
    }

    /**
     * Finds the Aiport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Airport::findOne($id)) !== null) {
            return $model;
        } else {
        
           $this->setHeader(400);
	   echo json_encode(array('status'=>0,'error_code'=>400,'message'=>'Bad request'),JSON_PRETTY_PRINT);
	   exit;
           // throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    private function setHeader($status)
      {
	  
	  $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
	  $content_type="application/json; charset=utf-8";
	
	  header($status_header);
	  header('Content-type: ' . $content_type);
      }
    private function _getStatusCodeMessage($status)
    {
	// these could be stored in a .ini file and loaded
	// via parse_ini_file()... however, this will suffice
	// for an example
	$codes = Array(
	    200 => 'OK',
	    400 => 'Bad Request',
	    401 => 'Unauthorized',
	    402 => 'Payment Required',
	    403 => 'Forbidden',
	    404 => 'Not Found',
	    500 => 'Internal Server Error',
	    501 => 'Not Implemented',
	);
	return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
