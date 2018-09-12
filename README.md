# 家腾互联 API 文档 #

#### 1 规范说明

##### 1.1 通信协议

HTTPS协议

##### 1.2 请求方法
所有接口只支持POST方法发起请求。

##### 1.3 字符编码
HTTP通讯及报文BASE64编码均采用UTF-8字符集编码格式。

1、**web 注册**

>-**URL**

>[ https://easy.jiateng.wang/register/register_web](http://)


-**提交参数**

| 请求参数     | 请求类型 |是否可为空| 请求说明 |
| :---        | :---    | :---     | :---    |
| system_id   | int     |   否     | 系统ID   |
| merchant_id | int     |   是     | 商户ID   |
| shop_id     | int     |   是     | 店铺ID   |
| phone       | varchar |   否     | 手机号   |
| password    | varchar |   否     | 密码     |


-**返回值**
```php
{
    code:200,
    data:'',
    msg:'注册成功'

}
```

***


2、**web 登录**

>-**URL**

> [https://easy.jiateng.wang/login/login_web](http://)


-**提交参数**

| 请求参数     | 请求类型 |是否可为空| 请求说明 |
| :---        | :---    | :---     | :---    |
| phone       | varchar |   否     | 手机号   |
| password    | varchar |   否     | 密码     |


-**返回值**
```php
{
    code:200,
    data:{
            user_id:9,//用户ID
            token:sadfsdfsfexdfdssadsafdsafdsa,
            aging:7200,//有效时长
            creationtime:'2018-7-31 0:27:33'//创建时间
        },
    msg:'登录成功'

}
```




