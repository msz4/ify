<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
    }
    public function index()
	{
        $this->load->helper('url');
		$data['page'] = 'manage_bills';
        $this->load->view('admin/dashboard', $data); 
	}
    
     public function AllBills()
	{
        $output = '';
        $this->load->model('dashboard_Model');
        $data = $this->dashboard_Model->fetch_AllBills($this->input->post('limit'), $this->input->post('start'), 'all', 'all');
        if($data->num_rows() > 0)
      {
            foreach(array_chunk($data->result(), 2) as $pair) {
                $output .='<div class="row">';
                foreach ($pair as $row)
       {
         $tags="";
        if($row->bill_tag1!=""){$tags.="<a href='".site_url('bills/tag/'.$row->bill_tag1)."'>#".$row->bill_tag1."</a> ";}
        if($row->bill_tag2!=""){$tags.="<a href='".site_url('bills/tag/'.$row->bill_tag2)."'>#".$row->bill_tag2."</a> ";}
        if($row->bill_tag3!=""){$tags.="<a href='".site_url('bills/tag/'.$row->bill_tag3)."'>#".$row->bill_tag3."</a> ";}
        
        $status="";
        if($row->bill_status=="Passed"){$status.='Passed <i class="fas fa-check-circle" style="color:green;"></i> on '.$row->bill_thirdreading;}
        else if($row->bill_status=="In consideration"){$status.='In Consideration <i class="fa fa-clock" style="color:orange;"></i>';}
        else if($row->bill_status=="Thrown out"){$status.='Thrown out <i class="fa fa-ban" style="color:red;"></i>';}
        
        $pub_text="Publish";
        if($row->publish==1){$pub_text = "Unpublish";}
                    
        $output .= '<div class="col-lg-6"><div class="nopadding card shadow p-3 mb-5 rounded " style ="text-align:center;margin-bottom: 20px; padding:0px 0px 0px 0px;"><div class="card-header" style="padding:2px; background:#ffffff"><h5>'.$row->bill_question.'</h5></div><div class="card-body" style="padding:0"> <img src ="'.site_url('application/uploads/').$row->bill_img.'/><div class="card-header" style="padding:0;">'.$row->bill_number.', introduced on '.$row->bill_firstreading.'</div><hr/><div>'.$tags.'</div><hr/><div>'.$status.'</div><hr/><div>Created:'.$row->bill_createdon.' Edited:'.$row->bill_lasteditedon.'</div><hr/><div class="row btn-group"><div class="col-sm-3"><a class=" btn bg-secondary " style="color:white" href="'.site_url('bills/display/').$row->id.'" role="button">View</a></div><div class="col-sm-3"><a class="btn bg-info" style=" color:white" href="'.site_url('admin/dashboard/editBill/').$row->id.'" role="button">Edit</a></div><div class="col-sm-3"><a class="btn bg-success" style=" color:white" href="'.site_url('admin/dashboard/publishBill/').$row->id.'/'.$row->publish.'" role="button">'.$pub_text.'</a></div><div class="col-sm-3"><a class="btn bg-danger" name="delete" style="color:white" onclick="deleteBill('.$row->id.',\''.$row->bill_img.'\')" role="button">Delete</a></div></div></div></div>';
       }
                 $output .='</div>';
            }
      }
        echo $output;
    }
    
    public function deleteBill(){
        if ($this->ion_auth->is_admin())
		{
			$id = $this->input->post('id');
            $picFileName = $this->input->post('picName');
            $this->dashboard_Model->delete($id, $picFileName);
            return true;
		}
            return false;
    }
    
    public function publishBill($id, $initial){
       
            if($initial == 1){$new_data['publish'] =0;} else {$new_data['publish'] =1;}
            $this->dashboard_Model->publishBill($id, $new_data);
        
           
        
    }
    
    public function deleteLegistlator(){
        $id = $this->input->post('id');
        $result=$this->dashboard_Model->deleteLegistlator($id);
        echo $result;
    }
    
    public function addLegistlator(){
        $this->load->helper('url');
        $this->load->helper('form');
        if($_POST)
        {        
            $new_data['name'] = $this->input->post('name');
            $new_data['chamber'] = $this->input->post('chamber');
            $new_data['party']= $this->input->post('party');
            $new_data['state']= ($this->input->post('state'));
            $new_data['constituency']= ($this->input->post('constituency'));
            $new_data['term'] = $this->input->post('legistlator_term');
             
            $this->dashboard_Model->addLegistlator($new_data);       
        }
        $data['info'] = $this->dashboard_Model->getInfo();
        $this->load->view('admin/add_legistlator',$data);
    }
    
    public function addBill()
    {
        $this->load->helper('form');
        if($_POST)
        {
            $img_name = "bill".time();
            $image = FALSE;
            if($_FILES)
            {
                $image = $this->do_upload($img_name);
            }
            
            $new_data['bill_number'] = $this->input->post('billNumber');
            $new_data['bill_term'] = $this->input->post('billTerm');
            $new_data['bill_imagename'] = $img_name;
            $new_data['bill_title'] = $this->input->post('billTitle');
            $new_data['bill_sponsor']= $this->input->post('billSponsor');
            $new_data['bill_summary']= $this->input->post('billSummary');
            $new_data['bill_additionalinfo']= $this->input->post('billAdditionalinfo');
            $new_data['bill_origin']= $this->input->post('origin');
            $new_data['bill_trans'] = $this->input->post('trans');
            $new_data['bill_sponsor'] = $this->input->post('billSponsor');
            $new_data['bill_fulltext'] = $this->input->post('billFulltext');
            $new_data['bill_impact'] = $this->input->post('billImpact');
            $new_data['bill_tag1'] = $this->input->post('billTag1');
            $new_data['bill_tag2'] = $this->input->post('billTag2');
            $new_data['bill_tag3'] = $this->input->post('billTag3');
            $new_data['bill_news'] = $this->input->post('billNews');
            $new_data['bill_status'] = $this->input->post('billStatus');
            $new_data['bill_firstreading'] = $this->input->post('billFirstreading');
            $new_data['bill_secondreading'] = $this->input->post('billSecondreading');
            $new_data['bill_thirdreading'] = $this->input->post('billThirdreading');
            $new_data['bill_committeereport'] = $this->input->post('billReportoutofcommittee');
            $new_data['bill_committee'] = $this->input->post('billCommittee');
            $new_data['bill_remarks'] = $this->input->post('billRemarks');
            $new_data['bill_question'] = $this->input->post('billQuestion');
            $new_data['bill_createdon'] = date('Y/m/d H:i:s');
            $new_data['bill_lasteditedon'] = date('Y/m/d H:i:s');
            
            if($image)
            {
                $new_data['bill_img'] = $image;
            }
            
            $this->dashboard_Model->add($new_data);
            
        }
        
        $data['info'] = $this->dashboard_Model->getInfo();
        $data['page'] = 0;
        $data['committees'] = $this->dashboard_Model->getCommittees(); 
        $data['legistlators'] = $this->dashboard_Model->getLegistlators(); 
        $this->load->view('admin/add_bill', $data);
    }
    
    public function show($id)
    {   
        $data['id'] = $id;
        $this->load->view('show',$data);
    }
    
    public function manageLegistlators(){
        $this->load->helper('url');
		$data['legistlators'] = $this->dashboard_Model->allLegistlators();
        $this->load->view('admin/manage_legistlators', $data);
    }
    
    public function editLegistlator($id){
        $this->load->helper('url');
        $this->load->helper('form');
         if($_POST)
        {        
            
            $new_data['name'] = $this->input->post('name');
            $new_data['chamber'] = $this->input->post('chamber');
            $new_data['party']= $this->input->post('party');
            $new_data['state']= ($this->input->post('state'));
            $new_data['constituency']= ($this->input->post('constituency'));
             $new_data['term'] = $this->input->post('legistlator_term');
             
            $this->dashboard_Model->updateLegistlator($id, $new_data);       
        }
        
        $data['legistlator'] = $this->dashboard_Model->editLegistlator($id);
         $data['info'] = $this->dashboard_Model->getInfo();
        $this->load->view('admin/add_legistlator',$data);
    }
    
    public function getTermLegistlators(){
        $options='';
            $legistlators=$this->dashboard_Model->getTermLegistlators($this->input->post('term'),$this->input->post('chamber'));
            $sponsor = $this->input->post('sponsor');
            foreach($legistlators as $rep):
                
                if($rep['id']==$sponsor){
                    $options.= "<option value='".$rep['id']."' selected>".$rep['name']."</option>";
                }
        else{
            $options.= "<option value='".$rep['id']."'>".$rep['name']."</option>";
        }
            endforeach;
        echo ($options);
    }
    
    public function getTags(){
        $options='';
        $tags=$this->dashboard_Model->getTags($this->input->post('tag_column'));
        $selected_tag = $this->input->post('selected_tag');
        foreach($tags as $rep):
                
                if($rep==$selected_tag){
                    $options.= "<option value='".$rep."' selected>".$rep."</option>";
                }
        else{
            $options.= "<option value='".$rep."'>".$rep."</option>";
        }
            endforeach;
        echo ($options);

    }
    
    public function getCons(){
            $options='';
            $state = $this->input->post('state');
            $chamber_cons = $this->input->post('chamber_cons');
            $conss=$this->dashboard_Model->getCons($state, $chamber_cons);
            echo "<script> console.log('".$state."'); </script>";
            $cons = $this->input->post('cons');
            foreach($conss as $rep):
                
                if($rep==$cons){
                    $options.= "<option value='".$rep."' selected>".$rep." </option>";
                }
        else{
            $options.= "<option value='".$rep."'>".$rep." </option>";
        }
            endforeach;
        echo ($options);
        
    }
    
    public function getStates(){
            $options='';
            $states=$this->dashboard_Model->getStates();
            $state = $this->input->post('state');
            foreach($states as $rep):
                
                if($rep['state']==$state){
                    $options.= "<option value='".$rep['state']."' selected>".$rep['state']." </option>";
                }
        else{
            $options.= "<option value='".$rep['state']."'>".$rep['state']." </option>";
        }
            endforeach;
        echo ($options);
        
    }
    
    public function editBill($id)
    {   
        $this->load->helper('form');
        if($_POST)
        {
            if($_FILES['billimage']['name']=='')
            {
                
            }
            else{
                 $image = FALSE;
                if($_FILES)
                {
                    $image = $this->do_upload($this->input->post('billImagename'));
                }
                 
                if($image)
                {
                    $new_data['bill_img'] = $image;
                }
            }
           
            
             
            $new_data['bill_number'] = $this->input->post('billNumber');
            $new_data['bill_term'] = $this->input->post('billTerm');
            $new_data['bill_title'] = $this->input->post('billTitle');
            $new_data['bill_sponsor']= $this->input->post('billSponsor');
            $new_data['bill_summary']= $this->input->post('billSummary');
            $new_data['bill_additionalinfo']= $this->input->post('billAdditionalinfo');
            $new_data['bill_origin']= $this->input->post('origin');
            $new_data['bill_sponsor'] = $this->input->post('billSponsor');
            $new_data['bill_fulltext'] = $this->input->post('billFulltext');
            $new_data['bill_impact'] = $this->input->post('billImpact');
            $new_data['bill_tag1'] = $this->input->post('billTag1');
            $new_data['bill_tag2'] = $this->input->post('billTag2');
            $new_data['bill_tag3'] = $this->input->post('billTag3');
            $new_data['bill_news'] = $this->input->post('billNews');
            $new_data['bill_status'] = $this->input->post('billStatus');
            $new_data['bill_firstreading'] = $this->input->post('billFirstreading');
            $new_data['bill_secondreading'] = $this->input->post('billSecondreading');
            $new_data['bill_thirdreading'] = $this->input->post('billThirdreading');
            $new_data['bill_committeereport'] = $this->input->post('billReportoutofcommittee');
            $new_data['bill_committee'] = $this->input->post('billCommittee');
            $new_data['bill_remarks'] = $this->input->post('billRemarks');
            $new_data['bill_question'] = $this->input->post('billQuestion');
            $new_data['bill_lasteditedon'] = date('Y/m/d H:i:s');

            
            $this->dashboard_Model->update($id, $new_data);       
        }
         $data['info'] = $this->dashboard_Model->getInfo();
        $data['page'] = 1;
        $data['bill'] = $this->bills_Model->getBill($id);
        $data['committees'] = $this->dashboard_Model->getCommittees();
        $data['legistlators'] = $this->dashboard_Model->getLegistlators();
        $this->load->view('admin/add_bill',$data);
    }
    
    public function do_upload($name)
    {
        $config['upload_path'] = './application/uploads/';
        $config['allowed_types'] ='gif|jpg|png';
        $config['file_name'] = $name;
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->do_upload('billimage');
        $data = $this->upload->data();
        return $data['file_name']; 
    }
    
    public function db_test()
    {
        $this->Bill->connection_test();
    }
}
