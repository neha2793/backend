@inject('carbon', 'Carbon\Carbon')
<!DOCTYPE html>
<html lang="en">
<head>
  <title>2kpaid</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<body>

<style type="text/css">

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0px;
    background: #fff;
}
</style>

<div class="mail-messages-body-top" style="width: 640px;margin: 50px auto;">
    <div class="mail-messages-body" style="padding: 20px;background: #eaeaea;">

        <div class="mail-messages" style="background: #FFFFFF;padding: 30px;">
            <div class="mail-logo" style="margin-bottom: 20px;text-align: center;">
                <a href="#" style="display: inline-block; width: 160px;"><img src="{{$message->embed(public_path('assets/img/logo.png'))}}" class="img-fluid" alt="" style="max-width: 100%; height: auto;"></a>
            </div>
            <div class="mail-docu" style="padding: 28px 36px 36px 36px;border-radius: 2px;background-color: #000;text-align: center;margin-bottom: 20px;">
                <img src="assets/img/unnamed.png" class="img-fluid" alt="" style="max-width: 100%;height: auto;width: 100px;margin-bottom: 20px;">
                <h3 style="margin: 0px 0px 5px 0px;font-weight: 700;font-size: 16px;color: #fff;">Welcome to 2kpaid</h3>
                <p style="font-weight: 400;font-size: 16px;line-height: 24px;color: #fff;margin-bottom: 22px;margin-top: 0px;">Dear {{ucfirst($data['name'])}},</p>
                <p style="font-weight: 400;font-size: 16px;line-height: 24px;color: #fff;margin-bottom: 22px;margin-top: 0px;">Thank you for your application  at 2kpaid.</p>
                <p style="font-weight: 400;font-size: 16px;line-height: 24px;color: #fff;margin-bottom: 22px;margin-top: 0px;">We want to thank you for the time and energy you invested in your application.</p>
                <p style="font-weight: 400;font-size: 16px;line-height: 24px;color: #fff;margin-bottom: 22px;margin-top: 0px;">We received a large number of applications, and after carefully reviewing all of them, unfortunately, we have to inform you that this time we won’t be able to verify you’r account.</p>
                
            </div>
            <!-- <div class="namemail">
                
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;"><b>Product - </b>{{$data['product_name']}}</p>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;"><b>Price - </b>{{'$'.$data['amount']}}</p>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;"><b>Quantity - </b>{{$data['qty']}}</p>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;"><b>Order date - </b>{{$carbon::parse($data['Order_date'])->format('m/d/Y')}}</p>

                <h3 style="margin: 0px 0px 5px 0px;font-weight: 700;font-size: 20px;color: #000;">Address</h3>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;"> <b> {{ucfirst($data['Shipping_FirstName'])}},</b></p>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;">{{$data['Shipping_address1']}} {{$data['Shipping_address2']}}</p>
                <p style="font-size: 14px;margin: 0px 0px 10px 0px;color: #000;">{{$data['Shipping_city']}} {{$data['Shipping_state']}} {{$data['Shipping_Zipcode']}}</p>
            </div>   -->

        </div>

        <div class="mail-messages-bottom" style="padding: 30px 0px;">
            <ul style="margin: 0px;padding: 0px;list-style: none;">
                <li style="margin-bottom: 0px;">
                    <h5 style="margin: 0px 0px 7px 0px;font-size: 14px;font-weight: 600;color: #000;">Regards,</h5>
                    <p style="font-size: 14px;margin: 0px;color: #000;">Team 2kpaid.</p>
                </li>
            </ul>
        </div>

    </div>
</div>




</body>
</html>






