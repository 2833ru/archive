<?php
/**
 * ����������� �������� ������ �� 250 �������� � �����
 * @param array $obj ������
 */
function product_grid_spec_hook($obj,$row) {
		global $PHPShopModules,$promotionslist;

		$category = $row['category'];
		$uid = $row['id'];

		//������ �����
		$data = $promotionslist;

		//��������� ������ ���� ������ ����
	  if($data[0]['code']=='') {
	      $data[0] = $data;
	  }

	  //�������� ��������� � ������
	  unset($category_ar);
	  unset($products_ar);

		if(isset($data)) {
				foreach ($data as $key => $pro) {
					//������ ��������� ��� ����� ����
	        if($pro['categories_check']==1):
	            //��������� ������
	            $category_ar = explode(',', $pro['categories']);
	        endif;

	        if($pro['products_check']==1):
	            //��������� ������
	            $products_ar = explode(',', $pro['products']);
	        endif;

	        $sumche = 0;
          $sumchep = 0;

	        //������ �� ����� ����������
	        if(isset($category_ar)) {
	            foreach ($category_ar as $val_c) {
	                if($val_c==$category) {
	                    $sumche = 1;
	                    break;
	                }
	                else {
	                    $sumche = 0;
	                }
	            }
	        }

	        //������ �� ����� �������
	        if(isset($products_ar)) {
	            foreach ($products_ar as $val_p) {
	                if($val_p==$uid) {
	                    $sumchep = 1;
	                    break;
	                }
	                else {
	                    $sumchep = 0;
	                }
	            }
	        }

	        //�������� ��������� � ������
				  unset($category_ar);
				  unset($products_ar);


	        if($sumche==1 or $sumchep==1):
	        	//���� �������
	          if($pro['discount_tip']==1) {
	          	$pro['discount'];
	          	$discount[] = $pro['discount'];
	          }
	          if($pro['discount_tip']==0) {
	          	$pro['discount'];
	          	$discountsum[] = $pro['discount'];
	          }

	        endif;
				}
				//����� ����� ������� ������
				if(isset($discount))
					$discount = max($discount)/100;

				if(isset($discountsum))
					$discountsum = max($discountsum);
		}

		//���� ���� ������
		if($discount!='' or $discountsum!='') {
	    
	    $priceDiscount[] = $obj->price($row) - ($obj->price($row) * $discount);
	    $priceDiscount[] = $obj->price($row) - $discountsum;
	    $priceDiscounItog = min($priceDiscount);
	    $priceDiscount = $priceDiscounItog;
	    //�������� ���� � ����� ������
	    if($priceDiscount<0) {
	    	$priceDiscount = 0;
	    }

	    $productPrice = $priceDiscount;
	    $productPriceNew = $obj->price($row, true);
	    $obj->set('productPrice', $productPrice);
	    $obj->set('productPriceRub', PHPShopText::strike($obj->price($row) . " " . $obj->currency()));



	    //������ �����
      $obj->set('promotionsIcon', '<span class="sale-icon" style="background-color: rgba(115, 41, 2, 0.29) !important;">�����-�����</span>');
  	}
  	else {
      $obj->set('promotionsIcon', '');
  	}

}
 
$addHandler=array
        (
        'product_grid'=>'product_grid_spec_hook'
         );
?>