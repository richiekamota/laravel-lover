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
                                <div style="cursor:auto;color:#4f4f4f;font-family:Ubuntu;font-size:24px;font-weight:bold;line-height:40px;">
                                    <span style="color: #1b696e">Hang on a second!</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center">
                                <div style="cursor:auto;color:#58585d;font-family:Ubuntu;font-size:15px;line-height:22px;">
                                    Your application has been marked pending.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center">
                                <div style="cursor:auto;color:#58585d;font-family:Ubuntu;font-size:15px;line-height:22px;">
                                    The reason for the application being marked as pending is: {{$reason}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="word-break:break-word;font-size:0px;padding:10px 25px;" align="center">
                                <div style="cursor:auto;color:#58585d;font-family:Ubuntu;font-size:15px;line-height:22px;">
                                    Should you wish to query this application please reply to this email.
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