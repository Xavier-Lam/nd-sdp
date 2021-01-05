<?php
namespace ND\SDP\UC;

class SMSOP
{
    /**
     * 注册
     */
    const REGISTRER = 0;

    /**
     * 个人重置密码
     */
    const RESETPASSWORD_PERSONAL = 1;

    /**
     * 更新手机号码
     */
    const UPDATETEL = 2;

    /**
     * 短信登录
     */
    const LOGIN = 3;

    /**
     * 更新手机时旧手机的验证(内部逻辑使用)
     */
    const VALIDATECURRENTTEL = 5;

    /**
     * 组织帐户重置密码
     */
    const RESETPASSWORD_ORG = 6;

    /**
     * 注销用户
     */
    const DELETEACCOUNT = 7;
}