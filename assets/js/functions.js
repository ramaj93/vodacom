//JSON CRUD functions
//Coded by Ramadan Juma
var operation = "A"; //"A"=Adding; "E"=Editing
var selected_index = -1; //Index of the selected list item
var where = null;
var lsExist = false;



function array_combine(a, b)
{
    if (a.length != b.length)
    {
        return false;
    }
    else
    {
        new_array = new Array();
        for (i = 0; i < a.length; i++)
        {
            new_array[a[i]] = b[i];
        }

        return new_array;
    }
}

function get_price(value) {
    var gProd = get_where('products', {'code': value});
    //var id = JSON.parse(localStorage.session).id;
    //var whProd = get_where('warehouses_products',{});

    return gProd;
}



function Add() {
    var client = JSON.stringify({
        ID: $("#txtID").val(),
        Name: $("#txtName").val(),
        Phone: $("#txtPhone").val(),
        Email: $("#txtEmail").val()
    });
    db.push(client);
    localStorage.setItem("tbClients", JSON.stringify(db));
    alert("The data was saved.");
    return true;
}

function view_invoice(data) {

}

function submit_sale() {
    $quantity = "quantity";
    $product = "product";
    $unit_price = "price";
    $tax_rate = "tax_rate";
    $sl = "serial";
    $dis = "discount";

    $date = new Date('Y-m-d');
    $reference_no = getNextAI();
    $paid_by = $('#rpaidby');
    $count = $('#count');
    $count = $count - 1;
    $whid = getUserWarehouse().warehouse_id; //using a default WAREHOUSE_ID is prone to errors
    $warehouse_id = $whid;

    $biller_id = DBILLER;
    $biller_details = getBillerByID($biller_id);
    $biller_name = $biller_details.name;

    if ($('#delete_id')) {
        $did = $('#delete_id');
    } else {
        $did = NULL;
    }
    if ($customer_details = getCustomerByName($('#customer'))) {
        $customer_id = $customer_details.id;
        $customer_name = $customer_details.name;
    } else {
        $customer_details = getCustomerByName(DCUS);
        $customer_id = $customer_details.id;
        $customer_name = $customer_details.name;
    }

    if (DISCOUNT_OPTION == 1) {
        $inv_discount = DEFAULT_DISCOUNT;
    }
    if (TAX2) {
        $tax_rate2 = DEFAULT_TAX2;
    }

    $inv_total_no_tax = 0;

    for ($i = 1; $i <= 500; $i++) {
        if ($('#quantity ').i && $('product').i && $('unit_price').i) {

            if (TAX1) {
                $tax_id = $('tax_rate').i;
                $tax_details = getTaxRateByID($tax_id);
                $taxRate = $tax_details.rate;
                $taxType = $tax_details.type;
                $tax_rate_id = $tax_id;//array

                if ($taxType == 1 && $taxRate != 0) {
                    $item_tax = ($('quantity').$i) * ($('unit_price').$i) * $taxRate / 100;
                    $val_tax = $item_tax;//array
                } else {
                    $item_tax = $taxRate;
                    $val_tax = $item_tax;
                }

                if ($taxType == 1) {
                    $tax = $taxRate + "%";
                } else {
                    $tax = $taxRate;//array
                }
            } else {
                $item_tax = 0;
                $tax_rate_id = 0;
                $val_tax = 0;
                $tax = "";
            }
            if (DISCOUNT_METHOD == 1 && DISCOUNT_OPTION == 2) {

                $discount_id = $('#dis').$i;
                $ds_details = getDiscountByID($discount_id);
                $ds = $ds_details.discount;
                $dsType = $ds_details.type;
                $dsID = $discount_id;

                if ($dsType == 1 && $ds != 0) {
                    $val_ds = ($('#quantity').$i) * ($('unit_price').$i) * $ds / 100;
                } else {
                    $val_ds = $ds * ($('quantity').$i);
                }

                if ($dsType == 1) {
                    $discount = $ds + "%";
                } else {
                    $discount = $ds;
                }
            } else if (DISCOUNT_METHOD == 2 && DISCOUNT_OPTION == 2) {

                $discount_id = ($dis.$i);
                $ds_details = getDiscountByID($discount_id);
                $ds = $ds_details.discount;
                $dsType = $ds_details.type;
                $dsID = $discount_id;

                if ($dsType == 1 && $ds != 0) {
                    $val_ds = ((((($quantity.$i)) * (($unit_price.$i)) + $item_tax) * $ds) / 100);
                } else {
                    $val_ds = $ds * (($quantity.$i));
                }

                if ($dsType == 1) {
                    $discount = $ds + "%";
                } else {
                    $discount = $ds;
                }
            } else {
                $val_ds = 0;
                $dsID = 0;
                $discount = "";
            }
            if (PRODUCT_SERIAL) {
                $serial = ($sl.$i);
            } else {
                $serial = "";
            }
            $inv_quantity = ($quantity.$i);
            $inv_product_code = ($product.$i);
            $inv_unit_price = ($unit_price.$i);
            $inv_gross_total = ((($quantity.$i)) * (($unit_price.$i)));

            $inv_total_no_tax += ((($quantity.$i)) * (($unit_price.$i)));
        }
    }


    if (DISCOUNT_OPTION == 2) {
        $total_ds = array_sum($val_ds);
    } else {
        $total_ds = 0;
    }


    if (TAX1) {
        $total_tax = array_sum($val_tax);
    } else {
        $total_tax = 0;
    }


    if (($inv_product_code)) {
        $each($inv_product_code, function(index, value) {
            $product_details = getProductByCode($inv_product_code[index]);
            //var_dump($product_details);
            $product_id = $product_details.id;
            $product_name = $product_details.name;
            $product_code = $product_details.code;
            $product_unit = $product_details.unit;
        }
        );
    }

    $keys = ["product_id", "product_code",
        "product_name", "product_unit",
        "tax_rate_id", "tax", "quantity",
        "unit_price", "gross_total",
        "val_tax", "serial_no",
        "discount_val", "discount",
        "discount_id"];

    $items = new Array();
    $each(map(null,
            $product_id, $product_code,
            $product_name, $product_unit,
            $tax_rate_id, $tax,
            $inv_quantity,
            $inv_unit_price,
            $inv_gross_total,
            $val_tax, $serial,
            $val_ds, $discount,
            $dsID), function($key, $value) {
        $items = array_combine($keys, $value);
    });

    if (TAX2) {
        $tax_dts = getTaxRateByID($tax_rate2);
        $taxRt = $tax_dts.rate;
        $taxTp = $tax_dts.type;

        if ($taxTp == 1 && $taxRt != 0) {
            $val_tax2 = ($inv_total_no_tax * $taxRt / 100);
        } else {
            $val_tax2 = $taxRt;
        }
    } else {
        $val_tax2 = 0;
        $tax_rate2 = 0;
    }

    if (DISCOUNT_METHOD == 1 && DISCOUNT_OPTION == 1) {

        $ds_dts = getDiscountByID($inv_discount);
        $ds = $ds_dts.discount;
        $dsTp = $ds_dts.type;

        if ($dsTp == 1 && $ds != 0) {
            $val_discount = ($inv_total_no_tax * $ds / 100);
        } else {
            $val_discount = $ds;
        }
    } else if (DISCOUNT_METHOD == 2 && DISCOUNT_OPTION == 1) {

        $ds_dts = getDiscountByID($inv_discount);
        $ds = $ds_dts.discount;
        $dsTp = $ds_dts.type;

        if ($dsTp == 1 && $ds != 0) {
            $val_discount = ((($inv_total_no_tax + $total_tax + $val_tax2) * $ds) / 100);
        } else {
            $val_discount = $ds;
        }
    } else {
        $val_discount = $total_ds;
        $inv_discount = 0;
    }

    $gTotal = $inv_total_no_tax + $total_tax + $val_tax2 - $val_discount;

    $saleDetails = {
        'reference_no': $reference_no,
        'date': $date,
        'biller_id': $biller_id,
        'biller_name': $biller_name,
        'customer_id': $customer_id,
        'customer_name': $customer_name,
        'inv_total': $inv_total_no_tax,
        'total_tax': $total_tax,
        'total': $gTotal,
        'total_tax2': $val_tax2,
        'tax_rate2_id': $tax_rate2,
        'inv_discount': $val_discount,
        'discount_id': $inv_discount,
        'user': USER_NAME,
        'paid_by': $paid_by,
        'count': $count
    };
    //}

    if ($this.form_validation.run() == true && !empty($items)) {
        if ($suspend) {
            if (suspendSale($saleDetails, $items, $count, $did)) {
                //$this.session.set_flashdata('success_message', $this.lang.line("sale_suspended"));
                //redirect("module=pos", 'refresh');
            }
        } else {
            if ($saleID = addlocalSale($saleDetails, $items, $warehouse_id, $did)) {
                //$this.session.set_flashdata('success_message', $this.lang.line("sale_added"));
                //redirect("module=pos&view=view_invoice&id=" . $saleID, 'refresh');
            }
        }
    } else {

        //$data['message'] = (validation_errors() ? validation_errors() : $this.session.flashdata('message'));
        //$data['success_message'] = $this.session.flashdata('success_message');


        $data['customer'] = getCustomerById(DCUS);
        $data['biller'] = getBillerByID(DBILLER);
        $data['discounts'] = getAllDiscounts();
        $data['tax_rates'] = getAllTaxRates();
        $data["total_cats"] = categories_count();
        $data["total_cp"] = products_count(DCAT);
        if (DISCOUNT_OPTION == 1) {
            $discount_details = getDiscountByID(DEFAULT_DISCOUNT);

            $data['discount_rate'] = $discount_details.discount;
            $data['discount_type'] = $discount_details.type;
            $data['discount_name'] = $discount_details.name;
        }
        if (DISCOUNT_OPTION == 2) {
            $discount2_details = getDiscountByID(DEFAULT_DISCOUNT);
            $data['discount_rate2'] = $discount2_details.discount;
            $data['discount_type2'] = $discount2_details.type;
        }
        if (TAX1) {
            $tax_rate_details = getTaxRateByID(DEFAULT_TAX);
            $data['tax_rate'] = $tax_rate_details.rate;

            $data['tax_type'] = $tax_rate_details.type;
            $data['tax_name'] = $tax_rate_details.name;
        }
        if (TAX2) {
            $tax_rate2_details = getTaxRateByID(DEFAULT_TAX2);
            $data['tax_rate2'] = $tax_rate2_details.rate;
            $data['tax_name2'] = $tax_rate2_details.name;
            $data['tax_type2'] = $tax_rate2_details.type;
        }
        $data['products'] = $this.ajaxproducts(DCAT);
        $data['categories'] = $this.poscategories();

        //$data['page_title'] = $this.lang.line("pos_module");

        //$this.load.view('add', $data);
    }
}

function Edit() {
    db[selected_index] = JSON.stringify({
        ID: $("#txtID").val(),
        Name: $("#txtName").val(),
        Phone: $("#txtPhone").val(),
        Email: $("#txtEmail").val()
    });//Alter the selected item on the table
    localStorage.setItem("tbClients", JSON.stringify(db));
    alert("The data was edited.")
    operation = "A"; //Return to default value
    return true;
}

function Delete() {
    db.splice(selected_index, 1);
    localStorage.setItem("tbClients", JSON.stringify(db));
    alert("Client deleted.");
}

function get_local_products(category_id, page) {

    //$this.pagination.initialize($config);

    var pro = 1;
    var id = db.localDB.session['userdata'].id;

    var user_branchID = get_where('warehouses_users', {'user_id': id}).warehouse_id;

    if (category_id != '*') {
        products = get_where('products', {'category_id': category_id});

    }
    else {
        products = get('products');
        category_id = 0;
    }
    //console.log('Number of products:' + products.length);
    var prods = "<div>";
    for (product in products) {
        var count = products[product].id;
        if (count < 10) {
            count = "0" + (count / 100) * 100;
        }
        if (category_id < 10) {
            category_id = "0" + (category_id / 100) * 100;
        }
        mypro = get_where('warehouses_products', {'product_id': products[product].id, 'warehouse_id': user_branchID});
        if (products[product].image == 'no_image.jpg') {
            if (mypro.quantity > 0) {
                prods += "<button id=\"product-" + category_id + count + "\" type=\"button\" value='" + products[product].code + "' class=\"green\" ><i><img src=\"assets/uploads/thumbs/default.png\"></i><span><span>" + products[product].name + " [" + mypro.quantity + "]</span></span></button>";
            }
            else
                pro--;
        } else {
            if (mypro.quantity > 0) {
                prods += "<button id=\"product-" + category_id + count + "\" type=\"button\" value='" + products[product].code + "' class=\"green\" ><i><img src=\"assets/uploads/thumbs/default.png\"></i><span><span>" + products[product].name + " [" + mypro.quantity + "]</span></span></button>";
            }
            else
                pro--;
        }
        pro++;
    }

    PLIMIT = 30;
    if (pro <= PLIMIT) {
        for (i = pro; i <= PLIMIT; i++) {
            prods += "<button type=\"button\" value='0' class=\"tr\" style=\"cursor: default !important;\"><i></i><span></span></button>";
        }
    }
    prods += "</div>";

    if (page) {
        //echo '"'+ prods +'"';
        //echo prods;
    } else {
        //echo '"'.$prods.'"';
        //return prods;
    }
    return prods;
}

function List() {
    $("#tblList").html("");
    $("#tblList").html(
            "<thead>" +
            "	<tr>" +
            "	<th></th>" +
            "	<th>ID</th>" +
            "	<th>Name</th>" +
            "	<th>Phone</th>" +
            "	<th>Email</th>" +
            "	</tr>" +
            "</thead>" +
            "<tbody>" +
            "</tbody>"
            );

    for (var i in db) {
        var cli = JSON.parse(db[i]);
        $("#tblList tbody").append("<tr>" +
                "	<td><img src='img/plus-icon.png' alt='Edit" + i + "' class='btnEdit'/><img src='img/turkish.png' alt='Delete" + i + "' class='btnDelete'/></td>" +
                "	<td>" + cli.ID + "</td>" +
                "	<td>" + cli.Name + "</td>" +
                "	<td>" + cli.Phone + "</td>" +
                "	<td>" + cli.Email + "</td>" +
                "</tr>");
    }
}
