<?php

$cates = M('transaction')->distinct(true)->field('cate_id')->select();
$ids = array();
foreach( $cates as $k=>$v){
	$ids[] = $v['cate_id'];
}

$where = [];
$str = 't.belong != "" and t.user_id=u.id and p.user_id=t.user_id and p.transaction_id=t.id';

foreach ($ids as $cate_id) {
	if($cate_id!=2){
		//维修，求购，出售
		//设计，求职，招聘
		$flag=I('flag');
		if($flag){
			if($flag=='求购'||$flag=='招聘'){
				$str = $str. " transaction.flag ='' or transaction.flag = '".$flag."'";
			}else{
				$str = $str. " and transaction.flag ='".$flag."'";
			}
		}	
	}
	$where['_string'] = $str;
	$where['t.cate_id'] = $cate_id;

	$Model = new \Think\Model();
	$list[] = $Model->table(array('bs_transaction'=>'t', 'bs_user'=>'u', 'bs_transaction_pay' => 'p'))->where($where)
			->field('t.id,t.user_id,t.title,t.content,t.add_time,t.r_payment,t.is_top,t.hit,t.cate_id,t.foucsCount,t.user_type,t.flag,u.head_thumb,u.lng,u.lat')
			->limit($limit)
			->select();

}

foreach($list as $k=>$v){
	$list[$k]['from_id'] = $v['id'];
	//红点
	if($v['addtime']>$redTime){
		if(!$uid){
			$list[$k]['isRed']=FALSE;
		}else{
			$isClick=M('transaction_click')->where(array('fromid'=>$v['id'],'user_id'=>$uid))->count();
			if($isClick>0){
				$list[$k]['isRed']=FALSE;
			}else{
				$list[$k]['isRed']=TRUE;
			}
		}
	}else{
		$list[$k]['isRed']=FALSE;
	}

	//判断是否要付费
	if($v['r_payment']=='1'){
		if($uid){
			$list[$k]['messageCount'] = M('comment')->where(array('from_id'=>$v['id'],'user_send_id'=>$uid))->count();	
		}else{
			$list[$k]['messageCount'] = M('comment')->where(array('from_id'=>$v['id']))->count();	
		}		
		
		//判断是否超过72小时
		$qixian=$v['addtime']+120*3600;
		$nowTime=time();
		if($qixian>$nowTime){
			//付费
			$list[$k]['r_payment']=TRUE;
			if(!$uid){
				$list[$k]['lock']=TRUE;
			}else{
				$transactionPay=M('transaction_pay')->where(array('user_id'=>$uid,'from_id'=>$v['id']))->find();
				if($transactionPay){
					$list[$k]['lock']=FALSE;
				}else{
					$list[$k]['lock']=TRUE;
				}
			}
			$list[$k]['dueTime']=$qixian-$nowTime;
		}else{
			//免费
			//看你是VIP？
			if($v['is_top']=='1'){
				$list[$k]['r_payment']=TRUE;
				$list[$k]['lock']=TRUE;
				$list[$k]['dueTime']=strtotime('next sunday')-$nowTime;
			}else{
				$list[$k]['r_payment']=FALSE;
				$list[$k]['lock']=FALSE;
				$list[$k]['dueTime']=0;
			}
		}
	}
	else{
		$list[$k]['messageCount'] = M('comment')->where(array('from_id'=>$v['id']))->count();
		$list[$k]['r_payment']=FALSE;
		$list[$k]['lock']=FALSE;
		$list[$k]['dueTime']=0;
	}

	//自己发布的已经解锁
	if($uid==$v['user_id']){
		$list[$k]['lock']=FALSE;
		$list[$k]['dueTime']=0;
	}

	$list[$k]['addtime']=mdate($v['addtime']);
	$list[$k]['distance']=$this->_getDistance($lat,$lng,$v['lat'],$v['lng']);

	$data_album=M('data_album')->where(array('fromid'=>$v['id']))->order('addtime')->find();
	$list[$k]['promin_img']=$data_album['img_thumb'];
	if(!$v['promin_img']){
		$publisherInfo=M('admin_customer')->where(array('id'=>$v['publisher']))->find();
		$list[$k]['promin_img']=$publisherInfo['head_img'];
		if(!$list[$k]['promin_img']){
			$list[$k]['promin_img']=$v['head_thumb'];
		}
	}else{
		$list[$k]['promin_img']='';
	}

	if($v['belong']){
		$list[$k]['customer']=TRUE;
		$list[$k]['isPc']=TRUE;
		$list[$k]['isPay']=TRUE;
		$list[$k]['isShang']=TRUE;
	}else{
		$list[$k]['customer']=FALSE;
		$list[$k]['isPc']=FALSE;
		$list[$k]['isPay']=FALSE;
		$list[$k]['isShang']=FALSE;
	}

	if($v['cate_id']=='5'){
		if($list[$k]['label_name']!=''){
			$list[$k]['cz']=explode(',', $list[$k]['label_name']);
			for ($lo=0; $lo < count($list[$k]['cz']); $lo++) { 
				if($lo<=2){
					$list[$k]['marks'][$lo]=$list[$k]['cz'][$lo];
				}
			}
			unset($list[$k]['cz']);
		}else{
			$list[$k]['marks']=array();
		}
	}else{
		$list[$k]['marks']=array();
	}
	
	if($v['flag']=='求职'&&$v['fake_user']=='1'){
		$list[$k]['addtime']='';
		$list[$k]['distance']='';
		$list[$k]['isRed']=FALSE;
	}

	}