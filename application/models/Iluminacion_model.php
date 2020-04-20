<?php		
if(! defined('BASEPATH')) exit('No direct script access allowed');

class Iluminacion_model extends CI_Model
{
	public function __Construct(){
		parent::__construct();
	}

	public function IdCompany($company){
	   	$this->db->select('id_empresa');//the name of fields to query in the login
      	$this->db->from('us_empresa');#name of first table
      	$this->db->join('empresa','empresa_id_empresa=id_empresa');
      	$this->db->where('empresa_nom', $company);#the field must match the entered parameter of password
      	$query = $this->db->get();#the query is obtained and stored within the variable
      	$result = $query->row();#the result displays in a row
      	return $result;#if the query has data, returns the data query
      }

      public function GetAllMeasurements(){
      	$q = $this->db->get('unidades_de_medida');
      	if($q -> num_rows() >0){
      		return $q;
      	}else{
      		return false;
      	}
      }

      public function GetInventorie_Products($idcompany){
      	$this->db->select('id_prod_alm, prod_alm_nom, unidad_medida, prod_alm_modelo, prod_alm_prec_unit, prod_alm_precio_venta, prod_alm_exist, prod_alm_codigo, prod_alm_descripcion, prod_alm_coment');
      	$this->db->from('producto_almacen');
      	$this->db->join('unidades_de_medida','prod_alm_medida=id_uMedida');
      	$this->db->where('empresa_id_empresa',$idcompany);
      	$result=$this->db->get();
      	return $result;
      }

      public function Edit_Product($id_prod,$data){
      	$this->db->where('id_prod_alm',$id_prod);
      	$this->db->update('producto_almacen', $data);
      	if ($this->db->affected_rows() > 0) {
      		return true;
      	} else{
      		return false;
      	}
      }

      public function New_Product($data){
      	$this->db->insert('producto_almacen', $data);
      	if ($this->db->affected_rows() > 0) {
      		return true;
      	} else{
      		return false;
      	}
      }

      public function GetInventorie_Office($idcompany){
      	$this->db->select('id_prod, producto_consu_nom, unidad_medida, producto_consu_prec_unit, producto_consu_exist, producto_consu_ult_compra, producto_consu_periodicidad, producto_consu_prox_compra, catalogo_proveedor_empresa');
      	$this->db->from('producto_consumible');
      	$this->db->join('unidades_de_medida','producto_consu_medida=id_uMedida');
      	$this->db->join('catalogo_proveedor','producto_consu_ult_proveedor=id_catalogo_proveedor');
      	$this->db->where('producto_consumible.empresa_id_empresa',$idcompany);
      	$result=$this->db->get();
      	return $result;
      }

      public function GetAll_Provider($idcompany){
      	$this->db->select('id_catalogo_proveedor, catalogo_proveedor_nom_fiscal, catalogo_proveedor_empresa, rfc, catalogo_proveedor_contacto1, catalogo_proveedor_contacto2, catalogo_proveedor_puesto1, catalogo_proveedor_puesto2, catalogo_proveedor_tel1, catalogo_proveedor_tel2, catalogo_proveedor_cel1, catalogo_proveedor_cel2, catalogo_proveedor_email1, catalogo_proveedor_email2, catalogo_proveedor_coment');
      	$this->db->from('catalogo_proveedor');
      	$this->db->where('empresa_id_empresa', $idcompany);
      	$result=$this->db->get();
      	return $result;
      }

      public function   Update_Consumible($id, $data){
      	$this->db->where('id_prod', $id);
      	$this->db->update('producto_consumible', $data);
      	if ($this->db->affected_rows() > 0) {
      		return true;
      	} else{
      		return false;
      	}
      }

      public function New_Consumible($data){
      	$this->db->insert('producto_consumible', $data);
      	if ($this->db->affected_rows() > 0) {
      		return true;
      	} else{
      		return false;
      	}
      }


  public function GetAllCustomer_Project($idcompany){
  		$this->db->select('id_obra_cliente, obra_cliente_nombre,catalogo_cliente_empresa, obra_cliente_imp_total, obra_cliente_saldo, obra_cliente_pagado, obra_cliente_estado, obra_cliente_comentarios');
  		$this->db->from('obra_cliente');
      $this->db->join('catalogo_cliente','obra_cliente_id_cliente=id_catalogo_cliente');
  		$this->db->where('obra_cliente.empresa_id_empresa',$idcompany);
  		$query = $this->db->get();
  		return $query;			
  }

  public function Get_Customer_List($idcompany){
      $this->db->select('id_catalogo_cliente,catalogo_cliente_empresa');
      $this->db->from('catalogo_cliente');
      $this->db->where('empresa_id_empresa',$idcompany);
      $query=$this->db->get();
      return $query;
  }

	public function AddCustomer_Project($data){
		$this->db->insert('obra_cliente',$data);
    if ($this->db->affected_rows() > 0) {
      return 1;
    } else{
      return 2;
    }
	}

  public function SumPagos_Obra($id_obra){
    $this->db->select_sum('venta_mov_monto','suma_pagos');
    $this->db->from('venta_movimiento');
    $this->db->Where('obra_cliente_id_obra_cliente',$id_obra);
    $query = $this->db->get();
    $result = $query->row();
    return $result; 
  }

  public function Edit_CustomerProject($id,$data){
    $this->db->where('id_obra_cliente', $id);
    $this->db->update('obra_cliente', $data);
    if ($this->db->affected_rows() > 0) {
      return 1;
    } else{
      return 2;
    }
  }

	 public function GetAllCustomer_Payments($idcompany){
    $this->db->select('id_obra_cliente, obra_cliente_nombre,catalogo_cliente_empresa, obra_cliente_imp_total, obra_cliente_pagado, obra_cliente_saldo, obra_cliente_ult_pago, obra_cliente_comentarios');
      $this->db->from('obra_cliente');
      $this->db->join('catalogo_cliente','obra_cliente_id_cliente=id_catalogo_cliente');
      $this->db->where('obra_cliente.empresa_id_empresa',$idcompany);
      $this->db->where('obra_cliente_estado',1);
      $query = $this->db->get();
      return $query; 
  }

  public function AddCustomer_Pay($data){
  	$this->db->insert('venta_movimiento', $data);
  	if ($this->db->affected_rows() > 0) {
			return 1;
		} else{
			return 2;
		}
  }

  public function Total_obra($id_obra){
    $this->db->select('obra_cliente_imp_total');
    $this->db->from('obra_cliente');
    $this->db->where('id_obra_cliente',$id_obra);
    $query=$this->db->get();
    $result = $query->row();
    return $result;
  }

  public function Fecha_Ult_Pago($new_id_obra){
    $this->db->select_max('venta_mov_fecha');
    $this->db->from('venta_movimiento');
    $this->db->where('obra_cliente_id_obra_cliente',$new_id_obra);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function UpdatePaysCustomer($id_obra,$data){
    $this->db->where('id_obra_cliente', $id_obra);
    $this->db->update('obra_cliente', $data);
    if ($this->db->affected_rows() > 0) {
      return 1;
    } else{
      return 2;
    }
  }

  public function Datos_obra($id_obra){
  	$this->db->select('obra_cliente_nombre, obra_cliente_imp_total, obra_cliente_pagado, obra_cliente_saldo, obra_cliente_comentarios');
  	$this->db->from('obra_cliente');
  	$this->db->Where('id_obra_cliente',$id_obra);
  	$query=$this->db->get();
  	$result=$query->row();
  	return $result;
  }

  public function GetPayments_List($id_obra){
  	$this->db->select('id_venta_mov, venta_mov_fecha, venta_mov_comentario, venta_mov_monto');
  	$this->db->from('venta_movimiento');
  	$this->db->where('obra_cliente_id_obra_cliente',$id_obra);
  	$this->db->order_by('venta_mov_fecha');
  	$result=$this->db->get();
  	return $result;
  }

  public function UpdateProject_Pay($data,$id){
    $this->db->where('id_venta_mov', $id);
    $this->db->update('venta_movimiento', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Id_Proyecto($id_movimiento){
    $this->db->select('obra_cliente_id_obra_cliente');
    $this->db->from('venta_movimiento');
    $this->db->where('id_venta_mov',$id_movimiento);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Update_Provider($id_prov,$data){
    $this->db->where('id_catalogo_proveedor', $id_prov);
    $this->db->update('catalogo_proveedor', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

    public function New_Provider($data){
    $this->db->insert('catalogo_proveedor', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

	public function GetAllProducts($idcompany){
    $this->db->select('id_catalogo_producto, catalogo_producto_nombre, catalogo_producto_umedida, catalogo_producto_precio, catalogo_proveedor_empresa, catalogo_producto_fecha_actualizacion, empresa_id_empresa, unidad_medida, catalogo_proveedor_empresa_id_empresa, catalogo_producto_url_imagen');
    $this->db->from('catalogo_producto');
    $this->db->join('unidades_de_medida', 'id_uMedida = catalogo_producto_umedida');
    $this->db->join('catalogo_proveedor', 'catalogo_proveedor_id_catalogo_proveedor = id_catalogo_proveedor');
    $this->db->join('empresa', 'empresa_id_empresa = id_empresa');
    $this->db->where('empresa_id_empresa', $idcompany);
    $query = $this->db->get();
    if($query -> num_rows() >0){
      return $query;
    }else{
      return $query;
    }
  }

  public function UpdateProduct($id, $data){
      $this->db->where('id_catalogo_producto', $id);
      $this->db->update('catalogo_producto', $data);
      if ($this->db->affected_rows() > 0) {
        return true;
      } else{
        return false;
      }
  }

  // this function can insert in any table of bd specifying th ename of table as parameter
  public function Insert($table, $data){
    $this->db->insert($table, $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function GetAll_Customer($idcompany){
    $this->db->select('id_catalogo_cliente, catalogo_cliente_nom_fiscal, catalogo_cliente_empresa, catalogo_cliente_rfc, catalogo_cliente_contacto1, catalogo_cliente_contacto2, catalogo_cliente_puesto1, catalogo_cliente_puesto2, catalogo_cliente_tel1, catalogo_cliente_tel2, catalogo_cliente_cel1, catalogo_cliente_cel2, catalogo_cliente_email1, catalogo_cliente_email2, catalogo_cliente_coment');
    $this->db->from('catalogo_cliente');
    $this->db->where('empresa_id_empresa', $idcompany);
    $result=$this->db->get();
    return $result;
  }
  
  public function GetAll_Anticipos(){
    $this->db->select('id_anticipo, obra_cliente_id_obra_cliente, catalogo_cliente_empresa, anticipo_fecha_deposito, anticipo_total, anticipo_pago, anticipo_resto, anticipo_status, anticipo_fecha_finiquito, anticipo_fecha_entrega, anticipo_coment');
    $this->db->from('anticipo');
    $this->db->join('catalogo_cliente', 'obra_cliente_id_obra_cliente=id_catalogo_cliente');
    $result=$this->db->get();
    return $result;
  }

  public function Update_Customer($id_cust,$data){
    $this->db->where('id_catalogo_cliente', $id_cust);
    $this->db->update('catalogo_cliente', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
      } else{
      return false;
    }
  }

  public function New_Customer($data){
    $this->db->insert('catalogo_cliente', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function New_Anticipo($data){
    $this->db->insert('anticipo',$data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }
  
  public function Update_Anticipo($data,$id_anticipo){
    $this->db->where('id_anticipo', $id_anticipo);
    $this->db->update('anticipo', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Add_Anticipo_product($data){
    $this->db->insert('prod_anticipo',$data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Get_Total_Anticipo($id_anticipo){
    $this->db->select_sum('(prod_anticipo_cantidad) * (prod_anticipo_precio_venta)','total');
    $this->db->from('prod_anticipo');
    $this->db->where('anticipo_id_anticipo',$id_anticipo);
     $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Get_Pagado_Anticipo($id_anticipo){
    $this->db->select('anticipo_pago');
    $this->db->from('anticipo');
    $this->db->where('id_anticipo',$id_anticipo);
     $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Get_Inventorie_Product($id_producto){
    $this->db->select('prod_alm_exist');
    $this->db->from('producto_almacen');
    $this->db->where('id_prod_alm',$id_producto);
     $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Actualiza_producto($id_producto,$data){
    $this->db->where('id_prod_alm', $id_producto);
    $this->db->update('producto_almacen', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Get_Anticipo_Product_List($id_anticipo){
    $this->db->select('id_prod_anticipo, anticipo_id_anticipo, producto_almacen_id_prod_alm, prod_alm_nom, prod_anticipo_cantidad, prod_anticipo_precio_venta, prod_anticipo_coment');
    $this->db->from('prod_anticipo');
    $this->db->join('producto_almacen','producto_almacen_id_prod_alm=id_prod_alm');
    $this->db->where('anticipo_id_anticipo', $id_anticipo);
    $result=$this->db->get();
    return $result;
  }

  public function Get_Anticipo_Info($id_anticipo){
    $this->db->select('id_anticipo, obra_cliente_id_obra_cliente, catalogo_cliente_empresa, anticipo_fecha_deposito, anticipo_total, anticipo_pago, anticipo_resto, anticipo_status, anticipo_fecha_finiquito, anticipo_fecha_entrega, anticipo_coment');
    $this->db->from('anticipo');
    $this->db->join('catalogo_cliente','obra_cliente_id_obra_cliente=id_catalogo_cliente');
    $this->db->where('id_anticipo', $id_anticipo);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Update_Prod_Anticipo($data,$id_prod_ant){
    $this->db->where('id_prod_anticipo', $id_prod_ant);
    $this->db->update('prod_anticipo', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Delete_Product_Ant($id_prod_ant){
    $this->db->where('id_prod_anticipo', $id_prod_ant);
    $this->db->delete('prod_anticipo');
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function AddPay_Anticipo($data){
    $this->db->insert('pagos_anticipo',$data);
    if ($this->db->affected_rows() > 0) {
      $id=$this->db->insert_id();
      return $id;
    } else{
      return false;
    }
  }

  public function UpdatePay_Anticipo($data,$id_pagos_anticipo){
    $this->db->where('id_pagos_anticipo', $id_pagos_anticipo);
    $this->db->update('pagos_anticipo', $data);
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }

  public function Get_Fecha_pago($id_anticipo){
    $this->db->select_max('pagos_anticipo_fecha');
    $this->db->from('pagos_anticipo');
    $this->db->where('id_anticipo',$id_anticipo);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Get_Pagos($id_anticipo){
    $this->db->select_sum('pagos_anticipo_cantidad','total_pagos');
    $this->db->from('pagos_anticipo');
    $this->db->where('id_anticipo',$id_anticipo);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Get_Anticipo_Pay_List($id_anticipo){
    $this->db->select('id_pagos_anticipo, id_anticipo, pagos_anticipo_fecha, pagos_anticipo_cantidad, pagos_anticipo_coment, pagos_anticipo_url_comprobante');
    $this->db->from('pagos_anticipo');
    $this->db->where('id_anticipo',$id_anticipo);
    $result=$this->db->get();
    return $result;
  }

  public function Get_url_comprobante_Pago($id_pagos_anticipo){
     $this->db->select('pagos_anticipo_url_comprobante');
    $this->db->from('pagos_anticipo');
    $this->db->where('id_pagos_anticipo',$id_pagos_anticipo);
    $query=$this->db->get();
    $result=$query->row();
    return $result;
  }

  public function Delete_Pay_anticipo($id_pagos_anticipo){
    $this->db->where('id_pagos_anticipo', $id_pagos_anticipo);
    $this->db->delete('pagos_anticipo');
    if ($this->db->affected_rows() > 0) {
      return true;
    } else{
      return false;
    }
  }
  
}





