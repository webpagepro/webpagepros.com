<?php


 class AssetDB
{

private $server = 'localhost';
private $user = 'phxcrimi_assets';
private $pass = 'P@ssw0rd';
private $dbase = 'phxcrimi_inventory';
private $adb;
      public $error_message;
    //  public $error_message  = '../../errors/db_error_connect.php';

    public function __construct(){
     $this->adb = new mysqli($this->server, $this->user, $this->pass, $this->dbase);
     //$error_message = $this->adb->mysqli_connect_errno;
     if($this->adb->connect_error)
     {
        die("Failed to Connect AssetDB: (" . $this->mysqli_connect_errno  . " ) " .  $this->mysqli->connect_error);
     }

      else{ 
    //      echo "  AsssetDB - CONNECTED    ";
          }

      return $this->adb;
  }

  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function get_qr_code($asset_id)
    {
          $db = $this->adb;
          $query = "SELECT asset_id FROM assets
                   WHERE $asset_id = ?";
          if($stmt = $db->prepare($query))
          {
            $stmt->bind_param("i", $asset_id);

           $success = $stmt->execute();
            if ($success){
                $count = $db->affected_rows;
                $stmt->close();
                return $count;
            }

            else {

                print_r($db->error);
            }

        }


    }
  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  public function create_asset_id($asset_id, $cateogory_id)
  {
        $query = "INSERT INTO assets(asset_id, category_id)
                 VALUES (?,?)";
        if($stmt = $this->adb->prepare($query))
        {
          $stmt->bind_param("ii", $asset_id, $cateogory_id);
          $stmt->execute();
          $stmt->close();
        }
  }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   public function get_assets_by_category($category_id) {
      $db = $this->adb;
       $query = "SELECT *
                 FROM assets
                 WHERE category_id = $category_id";
       $result = $db->query($query);
       if ($result == false) {
           print_r($db->error);
       }
       $assets = array();
       for ($i = 0; $i < $result->num_rows; $i++) {
           $items[] = $result->fetch_assoc();
           $assets[] = $items;
       }
       $result->free();
       return $items;
   }

//==============================================================================

    public function get_attributes($category_id)
    {
        // $db = new Database();
        $db = $this->adb;
         $attributes = array();
         $sql = "SELECT a.asset_id, a.category_id, attr.attribute_name, ata.attribute_value, ata.attribute_id
                                                      JOIN assets a
                                                       ON a.asset_id = ata.asset_id
                                                       JOIN attributes attr
                                                       ON ata.attribute_id = attr.attribute_id
                                                       WHERE a.category_id = $category_id";

        $result = $db->query($sql);

        if($result == FALSE)
          {
             print_r($db->error);
             exit;
          }

             $assets = array();

    while($row = $result->fetch_assoc())
        {
              $asset_id = $row['asset_id'];
              $attribute_name = $row['attribute_name'];
              $attribute_value = $row['attribute_value'];

                if(! isset($assets[$asset_id]))
                {
                    $assets[$asset_id] = array();
                    $assets[$asset_id]['attributes'] = array();
                }


              $assets[$asset_id]['attributes'][$attribute_name] = $attribute_value;
        }
               //  $attributes[]  = array("attribute_name" => $row['attribute_name'], "attribute_value" => $row['attribute_value']);//array($row['attribute_name'] => $row['attribute_value']); //
         return $assets;
      }

//==============================================================================
      function get_asset($asset_id) {
          //  global $db;
        //  $db = new AssetDB();
           $db = $this->adb;
        //  $this->db = $this->adb;
          $query = "SELECT * FROM assets WHERE asset_id = '$asset_id'";
          $stmt = $db->query($query);
          if ($stmt == false) {
              print_r($db->error);
          }
          $asset = $stmt->fetch_assoc();
          return $asset;
      }

//==============================================================================
      function get_assets($category_id)
      {

        $db = $this->adb;
           $attributes = array();
           $sql = "SELECT a.asset_id, a.category_id, ata.attribute_id, attr.attribute_name, ata.attribute_value
                                                         FROM asset_to_attributes ata
                                                         JOIN assets a
                                                         ON a.asset_id = ata.asset_id
                                                         JOIN attributes attr
                                                         ON ata.attribute_id = attr.attribute_id
                                                         WHERE a.category_id = $category_id
                                                         ORDER BY a.asset_id ASC";
        //  $result = $db->query($sql);
          $result = $db->query($sql);
          if($result == FALSE)
            {
               print_r($db->error);
               echo "138";//124
               exit;
            }

          //$attribute_cnt =
    //      $cbd = new CategoryDB();
      //    $cbd->category_lookup($category_id);
          //$cnt = count($attribute_cnt);

         $assets = array();

          while($row = $result->fetch_assoc())
          {
                    $asset_id = $row['asset_id'];
                    $attribute_name = $row['attribute_name'];
                    $attribute_value = $row['attribute_value'];

                    if(! isset($assets[$asset_id]))
                    {
                        $assets[$asset_id] = array();

                        $assets[$asset_id]['attributes'] =  array();
                  }

                  $assets[$asset_id]['attributes'][$attribute_name] = $attribute_value;

            }
          //===========<pre>";
          //  print_r($assets); echo "<br>";
    //       "===============================</pre>";
              return $assets;

      }

//==============================================================================

    public function delete_asset($asset_id) {
      //  $db = Database::getDB();
        $adb = new AssetDB();
        $query = "DELETE FROM assets
                  WHERE asset_id = '$asset_id'";
        $row_count = $adb->exec($query);
        return $row_count;
    }

//==============================================================================

  public function add_asset($asset_id, $category_id, $category_name, $asset_name, $asset_attributes) {
        global $adb;
        $query = "INSERT INTO assets
                     (asset_id, category_id, category_name, asset_name, asset_attributes[])
                  VALUES
                     ('$asset_id', '$category_id', '$category_name', '$asset_name', '$asset_attributes')";
        $db->exec($query);
    }

//==============================================================================
    //EDIT Asset
  public function edit_asset($asset_id, $asset_name, $category_id, $category_name,  $asset_attributes) {
          $db = $this->adb;
         $query = "UPDATE inventory.assets
                  SET assets.asset_id = '$asset_id', assets.asset_name = '$asset_name', asset.asset_attributes = '$asset_attributes'
                  WHERE assets.asset_id = '$asset_id'";
        $adb->exec($query);
     }


 public function update_asset($aid, $cid)
 {
             $db = $this->adb;
             $query = 'UPDATE phxcrimi_inventory.assets
                   SET assets.category_id = ?
                   WHERE assets.asset_id = ?';
             $stmt = $db->prepare($query);

             if ($stmt == false)
             {
                 print_r($db->error);
             }

         $stmt->bind_param("ii", $aid, $cid);
         $success = $stmt->execute();
         if ($success){
             $count = $db->affected_rows;
             $stmt->close();
             return $count;
         }

         else {
             print_r($db->error);
         }
     }



     public function twig_attributes($asset_id)
      {
           $db = new Database();//$this->adb;//global $db;
           $attributes = array();
           $query = "SELECT ata.asset_id, ata.attribute_id, attr.attribute_name, ata.attribute_value, atn.notes
                                                         FROM asset_to_attributes ata
                                                         JOIN attributes attr
                                                         ON ata.attribute_id = attr.attribute_id
                                                         JOIN asset_to_notes atn
                                                         ON atn.asset_id = ata.asset_id
                                                         WHERE ata.asset_id =  $asset_id";
         $result = $db->ExecuteQuery($query);
//  $result = $db->query($query);

           return $result;
     }

//==============================================================================

    public function category_attributes($asset_id)
     {
         //global $db;
       //$db = new AssetDB();
       $db = $this->adb;
         $attributes = array();
         $sql = "SELECT a.asset_id, cta.category_id, ata.attribute_id, attr.attribute_name, ata.attribute_value, atn.notes
                 FROM assets a
                 JOIN categories c
                 ON c.category_id = a.category_id
                 JOIN asset_to_attributes ata
                 ON ata.asset_id = a.asset_id
                 JOIN attributes attr
                 ON ata.attribute_id = attr.attribute_id
                 JOIN asset_to_notes atn
                 ON atn.asset_id = ata.asset_id
                 JOIN category_to_attributes cta
                 ON cta.category_id = a.category_id
                 WHERE ata.asset_id =  $asset_id
                 GROUP BY ata.attribute_id";

      $result = $db->query($sql);

       if($result == FALSE)
       {
           print_r($db->error . "  - cat_attributes");
           exit;
       }

       $asset = array();
       while($asset = $result->fetch_assoc())
       {
           $attributes[]  = $asset;

        }
           return $attributes;
      }


      function create_ata_no_record_found($asset_id, $attrs_id = array(), $attrs = array())
      {   // $attrs_id = array();
         $db = $this->adb;
          $q = "INSERT INTO phxcrimi_inventory.asset_to_attributes(asset_id, attribute_id, attribute_value)
                VALUES (?,?,?)";

     // for($i=0;$i<11;$i++)
     // {
     // $attrs_id++;

      $stmt = $db->prepare($q);
      $stmt->bind_param("iis", $asset_id, $attrs_id, $attrs);  //, $attribute_values); //$v[0], $v[1], $v[2], $v[3], $v[4]);
      $stmt->execute();
      return $stmt;
  //}
}


    public function notes_no_record_found($asset_id, $note_id, $note)
      {
           $db = $this->adb;
         $query = "INSERT INTO asset_to_notes(asset_id, note_id, notes)
            VALUES(?,?,?)";
          $stmt = $db->prepare($query);
          $stmt->bind_param('iis', $asset_id, $note_id, $note);
          $stmt->execute();
          $stmt->close();
        }


        public function get_asset_notes($asset_id)
        {
              //global $db;
              $db = $this->adb;
              $query = "SELECT notes
                       FROM asset_to_notes
                       WHERE asset_id = ?";
              $stmt = $adb->prepare($query);
              $stmt->bind_param('i', $asset_id);
              $stmt->execute();
            $stmt->close();

          }

//**********************************   Update Attributes ***********************************

      
      public function updater($attr_values = array(), $code, $attrs_id = array())
      {
           $db = $this->adb;
            $q = 'UPDATE phxcrimi_inventory.asset_to_attributes SET attribute_value = ? WHERE asset_id = ? AND attribute_id = ?';

                     $stmt = $db->prepare($q);
                     if ($stmt == false) {
                         print_r($db->error);
                         echo "line: 534 asset_db";
                     }


                      $stmt->bind_param("sii", $attr_values, $code, $attrs_id);
                      $success = $stmt->execute();
                      if($success){
                      $asset = $db->affected_rows;
                      $stmt->close();
                      return $asset;
                  } else {
                      print_r($db->error);
                  }
          }

//**********************************   Update Attributes ***********************************

   public function update_attribute_notes($notes, $code)
   {
      $db = $this->adb;
      $query = 'UPDATE phxcrimi_inventory.asset_to_notes
               SET notes = ? WHERE asset_id = ?';
       $stmt = $db->prepare($query);
       $stmt->bind_param('si', $notes, $code);
       $success = $stmt->execute();
       if ($success) {
          $count = $db->affected_rows;
          $stmt->close();
          return $notes;
         } else {
            print_r($db->error);
      }
  }
}
?>
