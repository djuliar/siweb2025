<!DOCTYPE html>
<html lang="en">
    <?php include 'db.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combo Box Dependensi</title>
</head>
<body>
    <h1>Combo Box Dependensi</h1>
    <form action="proses.php" method="post">
        <label for="provinsi">Provinsi:</label>
        <select name="provinsi" id="provinsi">
            <option value="">Pilih Provinsi</option>
            <?php
            $sql = mysqli_query($conn, "SELECT * FROM provinsi");
            while($row = mysqli_fetch_assoc($sql)){
                echo "<option value='".$row['id']."'>".$row['nama']."</option>";
            }
            ?>
        </select>

        <label for="kabupaten">Kabupaten:</label>
        <select name="kabupaten" id="kabupaten">
            <option value="">Pilih Kabupaten</option>
            <!-- Options will be populated here based on selected province -->
        </select>

        <label for="kecamatan">Kecamatan:</label>
        <select name="kecamatan" id="kecamatan">
            <option value="">Pilih Kecamatan</option>
            <!-- Options will be populated here based on selected district -->
        </select>

        <button type="submit">Submit</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#provinsi').on('change', function(){
                var provinsiId = $(this).val();
                if(provinsiId){
                    $.ajax({
                        type: 'POST',
                        url: 'get_kabupaten.php',
                        data: {provinsi_id: provinsiId},
                        success: function(html){
                            $('#kabupaten').html(html);
                            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>'); 
                        }
                    }); 
                }else{
                    $('#kabupaten').html('<option value="">Pilih Kabupaten</option>');
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>'); 
                }
            });

            $('#kabupaten').on('change', function(){
                var kabupatenId = $(this).val();
                if(kabupatenId){
                    $.ajax({
                        type: 'POST',
                        url: 'get_kecamatan.php',
                        data: {kabupaten_id: kabupatenId},
                        success: function(html){
                            $('#kecamatan').html(html);
                        }
                    }); 
                }else{
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>'); 
                }
            });
        });
    </script>
</body>
</html>