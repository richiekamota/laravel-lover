
@extends('emails.base')

@section('content')


<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
    <tr>
        <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:600px;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
           border="0">
        <tbody>
        <tr>
            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                <!--[if mso | IE]>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="vertical-align:top;width:75px;">
                <![endif]-->
                <div aria-labelledby="mj-column-px-75" class="mj-column-px-75 outlook-group-fix"
                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                        <tbody></tbody>
                    </table>
                </div>
                <!--[if mso | IE]>
                </td>
                <td style="vertical-align:top;width:450px;">
                <![endif]-->
                <div aria-labelledby="mj-column-px-450" class="mj-column-px-450 outlook-group-fix"
                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                        <tbody>
                        <tr>
                            <td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center">
                                <div style="cursor:auto;color:#58585d;font-family:Ubuntu;font-size:15px;line-height:22px;">
                                    The contract with the following details has been approved by the user.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center">
                                <div style="cursor:auto;color:#58585d;font-family:Ubuntu;font-size:15px;line-height:22px;">
                                    <ul style="list-style: none">
										<li><strong>Leaseholders name: </strong> {{$application->first_name}} {{$application->last_name}}</li>
									    <li><strong>Leaseholders email: </strong> {{$application->email}}</li>
									    <li><strong>Tenants name: </strong> {{$application->resident_first_name}} {{$application->resident_last_name}}</li>
									    <li><strong>Tenants email: </strong>{{$application->resident_email}}</li>
									    <li><strong>Tertiary Institution: </strong> {{$application->resident_study_location}}</li>
									    <li><strong>Unit Number: </strong> {{$unit->code}}</li>
									    <li><strong>Lease Start Date: </strong> {{$contract->start_date}}</li>
										<li><strong>Lease End Date: </strong> {{$contract->end_date}}</li>
										<li><strong>Lease Duration: </strong> {{$application->unit_lease_length}} months</li>
										@foreach ($contract->items as $item)
										<li><strong>Rental Item: </strong> {{$item->name}}</li>
										@endforeach
										<li><strong>Total Rental Amount: </strong>R {{number_format($item->sum('value'),2,".",",")}}</li>
									</ul>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!--[if mso | IE]>
                </td>
                <td style="vertical-align:top;width:75px;">
                <![endif]-->
                <div aria-labelledby="mj-column-px-75" class="mj-column-px-75 outlook-group-fix"
                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                        <tbody></tbody>
                    </table>
                </div>
                <!--[if mso | IE]>
                </td></tr></table>
                <![endif]--></td>
        </tr>
        </tbody>
    </table>
</div><!--[if mso | IE]>
</td></tr></table>
<![endif]-->

@endsection