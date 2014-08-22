/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (localStorage) {
    lsExist = true;
    db = null;
    modified = false;
    max_var = null;
    min_var = '';
    var jdb = localStorage.getItem('bestcare');
    db = JSON.parse(jdb);
    if (db) {    
        if(sessionStorage.logged_in == "1"){
        var localDB = {'sales': new Array(), 'session': new Object()};
        if (!db['localDB']) {//is buggy when user refreshes the page
            db['localDB'] = localDB; //add a local db table
            var userdata = {
                'id': localStorage.getObj('session').id
            };
            localStorage.setItem('session', '');
            db.localDB.session['userdata'] = userdata;
            sessionStorage.setItem('logged_in',1);
        }
        }
        else{
            sessionStorage.setItem('logged_in',1);
            window.location.reload(false);
        }
        $SETTING = db.settings;
        DEFAULT_INVOICE = $SETTING.default_invoice_type;
        DEFAULT_TAX = $SETTING.default_tax_rate;
        DEFAULT_TAX2 = $SETTING.default_tax_rate2;
        TAX1 = $SETTING.tax1;
        TAX2 = $SETTING.tax2;
        DEFAULT_WAREHOUSE = $SETTING.default_warehouse;
        CURRENCY_PREFIX = $SETTING.currency_prefix;
        NO_OF_ROWS = $SETTING.no_of_rows;
        TOTAL_ROWS = $SETTING.total_rows;
        ROWS_PER_PAGE = $SETTING.rows_per_page;
        PRODUCT_SERIAL = $SETTING.product_serial;
        DEFAULT_DISCOUNT = $SETTING.default_discount;
        DISCOUNT_OPTION = $SETTING.discount_option;
        DISCOUNT_METHOD = $SETTING.discount_method;
        BARCODE_SYMBOLOGY = $SETTING.barcode_symbology;
        SALES_REF = $SETTING.sales_prefix;
        QUOTE_REF = $SETTING.quote_prefix;
        PURCHASE_REF = $SETTING.purchase_prefix;
        TRANSFER_REF = $SETTING.transfer_prefix;
        CLIMIT = $SETTING.cat_limit;
        PLIMIT = $SETTING.pro_limit;
        DCAT = $SETTING.default_category;
        DCUS = $SETTING.default_customer;
        DBILLER = $SETTING.default_biller;
        DTIME = $SETTING.display_time;
        //ALERT_NO = get_total_results();

        //$user = $this->user()->row();	
        //FIRST_NAME = $user->first_name);
        //USER_NAME = $user->first_name." ".$user->last_name);
        //USER_ID = $user->id);
    }
    else {
        db = [];
    }
}

function page_reload(){
    localStorage.setItem('bestcare',JSON.stringify(db));
}

 function CallbackFunction(event) {

    if(window.event){

              if (window.event.clientX < 40 && window.event.clientY < 0) { 

                  alert("back button is clicked");    

              }else{

                  alert("refresh button is clicked");
              }

    }else{

        if (event.currentTarget.performance.navigation.type == 2) { 

            alert("back button is clicked");    

        }
        if (event.currentTarget.performance.navigation.type == 1){

            alert("refresh button is clicked");
         }             
    }
}
function getUserWarehouse($id) {
    if (!$id)
        $id = localStorage.session['id'];

    $q = get_where('warehouses_users', {'user_id': $id});
    if ($q) {
        return $q;
    }
    return false;
}

function _where(value) {
    where = value;
}
/*value jso var{keyid:value,prop:value}*/
function get_where(table, value, size) {
    var size_count = 1;
    if (size == null)
        size = 30;
    var pass = 0;
    var obj = db[table];
    var tmp_var = 0;
    var result = 0;
    for (var array_obj in obj) { //if its an array of object array_obj is an index 
        if (max_var) {
            //console.log('We reached here');
            if (parseInt(obj[array_obj][max_var]) > tmp_var) {
                //console.log('The now value:',result);
                tmp_var = parseInt(obj[array_obj][max_var]);
                result = obj[array_obj];

            }
        }
        else {
            //console.log('i shouldnt be here');
            for (property in obj[array_obj]) { //property of each object            
                count = Object.keys(value).length;
                //console.log('Number of required fields:' + count);

                whereprops = Object.keys(value);
                for (var whid = 0; whid < whereprops.length; whid++) { //keys of value
                    if (whereprops[whid] == property) {
//console.log(property + ' equals ' + whereprops[whid]);
                        if (obj[array_obj][property] == value[whereprops[whid]]) {
                            pass++;
                            //console.log(property+':'+obj[array_obj][property] + ' equals ' + whereprops[whid]+':'+ value[whereprops[whid]]+'ratio:'+pass+':'+count);

                        }
                    }
                }
            }
        }
        if (pass > 0 && size_count < (size + 1) && !max_var) {
            //console.log('i shouldnt be here');
            if (pass == count) {
                if (size_count == 1)
                    result = obj[array_obj]; //push back property
                else {
                    if (size_count == 2) {
                        var tmp = result;
                        result = [];
                        result.push(tmp);
                        result.push(obj[array_obj]);
                    }
                    else
                        result.push(obj[array_obj]);
                }
                size_count++;
            }
        }
        //console.log('Index:'+array_obj+'Result:'+result);
        pass = 0;
    }
    //console.log(result);
    max_var = 0;
    return result;
}
//insert into table values {value:prop}
function update(table, value) {
    var obj = db[table];
    for (var array_obj in obj) { //if its an array of object
        for (var property in obj[array_obj]) { //property of each object            
            whereprops = Object.keys(value);
            for (var whid = 0; whid < whereprops.length; whid++) { //keys of value
                if (whereprops[whid] == property) {
                    //obj[array_obj][property] == value[whereprops[whid]];
                    localStorage[array_obj][property] = value[whereprops][whid];
                    pass++;
                }
            }
        }
    }
    return pass;
}

function getCustomerByName($name)
{
    $q = get_where('customers', {'name': $name}, 1);
    if ($q)
    {
        return $q;
    }

    return false;
}
function get(table, size) {
    var result;
    if (where) {
        //console.log('get where');
        return get_where(table, where, size);
    }
    else if (max_var) {
        //console.log('get max var');
        result = get_where(table, max_var, 1);
        return result;
    }
    else {
        //console.log('else');
        result = db[table];
        if (result.length == 1) {
            return result[0];
        }
        return result;
    }
}

function insert(table, values) {
    var obj = db[table];
    //get index of the las object
    var inserted = 0;
    for (var index in values) {
        if (arrayHasOwnIndex(values, index)) {
            obj.push(values[index]);
            inserted++;
        }
    }
    localStorage.setItem('bestcare', JSON.Stringify(db));
    return inserted;
}

function getInvoiceBySaleID(sale_id)
{
    var q = get_where('sales', {'id': sale_id}, 1);
    if (q)
    {
        return q;
    }

    return FALSE;
}

function getAllInvoiceItems(sale_id)
{
    var q = get_where('sale_items', {'sale_id': sale_id});
    if (q) {
        return q;
    }
}

function getBillerByID(id)
{

    q = get_where('billers', {'id': id}, 1);
    if (q)
    {
        return q;
    }

    return false;
}
function getCustomerByID($id)
{

    q = get_where('customers', {'id': $id}, 1);
    if (q)
    {
        return q;
    }

    return false;
}

function getInvoiceTypeByID($id)
{

    q = get_where('invoice_types', {'id': $id}, 1);
    if (q)
    {
        return q;
    }

    return false;
}

function getSetting()
{
    q = get('pos_settings');
    if (q)
    {
        return q;
    }

    return false;
}

function select_max(value)
{
    max_var = value;
}
function getNextAI()
{
    select_max('id');
    q = get('sales');
    if (q)
    {
        //return SALES_REF."-".date('Y')."-".sprintf("%03s", $row->id+1);
        //return SALES_REF."-".sprintf("%04s", $row - > id + 1);
    }

    return false;
}

function addLocalSale($saleDetails, $items, $warehouse_id, $sid)
{
    var newSale = {
        'saleDetails': $saleDetails,
        'items': $items,
        'warehouse_id': $warehouse_id,
        'sid': $sid
    };
    db.localDB.sales.push(newSale);
    modified = true;
}
//function addSale($saleDetails, $items, $warehouse_id, $sid)
//{
//$each($items, function($index, $data){
//$product_id = $data['product_id'];
//        $product_quantity = $data['quantity'];
//        updateProductQuantity($product_id, $warehouse_id, $product_quantity); //req def
//});
//        // sale data
//        $saleData = array(
//                'reference_no' = > $saleDetails['reference_no'],
//                'warehouse_id' = > $warehouse_id,
//                'biller_id' = > $saleDetails['biller_id'],
//                'biller_name' = > $saleDetails['biller_name'],
//                'customer_id' = > $saleDetails['customer_id'],
//                'customer_name' = > $saleDetails['customer_name'],
//                'date' = > $saleDetails['date'],
//                'inv_total' = > $saleDetails['inv_total'],
//                'total_tax' = > $saleDetails['total_tax'],
//                'total' = > $saleDetails['total'],
//                'total_tax2' = > $saleDetails['total_tax2'],
//                'tax_rate2_id' = > $saleDetails['tax_rate2_id'],
//                'inv_discount' = > $saleDetails['inv_discount'],
//                'discount_id' = > $saleDetails['discount_id'],
//                'user' = > $saleDetails['user'],
//                'paid_by' = > $saleDetails['paid_by'],
//                'count' = > $saleDetails['count']
//                );
//        if (insert('sales', $saleData)) {
//$sale_id = insert_id();
//        $addOn = array('sale_id' = > $sale_id);
//        end($addOn);
//        $each($items, function($index, & $var){
//        $var = array_merge($addOn, $var);
//        });
//        if (insert_batch('sale_items', $items)) {
//if ($sid) { $this - > deleteSale($sid); }
//return $sale_id;
//}
//}
//
//return false;
//}
