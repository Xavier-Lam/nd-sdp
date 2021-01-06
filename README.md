# 网龙工程院共享平台(SDP) PHP SDK

由于代码早先封装,之后移动了结构,可能导致命名空间之类的异常,如有发现,可修改并提交.

- [Installation](#installation)
  - [直接安装](#直接安装)
  - [本地安装](#本地安装)
- [Quickstart](#quickstart)
  - [创建客户端](#创建客户端)
  - [新建会话](#新建会话)
  - [调用接口](#调用接口)
  - [MAC鉴权](#mac鉴权)
  - [BTS鉴权](#bts鉴权)
  - [租户](#租户)
- [Services](#services)
  - [敏感词服务](#敏感词服务)
- [Contributing](#contributing)
- [CHANGELOG](#changelog)

## Installation
### 直接安装
1. 登录 http://git.sdp.nd
2. 告知我们为你添加权限
3. 将本机的ssh密钥添加至 http://git.sdp.nd/profile/keys
4. 在composer.json 中添加

        "repositories": [
            {
                "type": "vcs",
                "url":  "git@git.sdp.nd:ndkfc/nd-sdp.git"
            }
        ]

5. composer require nd/sdp dev-master

### 本地安装
1. 将源码下载到本地
2. 在composer.json中添加

        "repositories": [
            {
                "type": "path",
                "url": "/full/or/relative/path/to/development/package"
            }
        ]

    建议使用项目的相对路径

3. 运行
        
        composer require "nd/sdp"

## Quickstart
### 创建客户端

     $client = new \ND\SDP\SdpClient();
     $env = \ND\SDP\Envs::PROD;
     $client->setEnv($env);

### 新建会话

    $appId = 'b4fb92a0-af7f-49c2-b270-8f62afac1133';
    $app = \ND\SDP\SdpApp::singleton($appId, $env);
    $session = \ND\SDP\UC\Session::current($app, $client);

### 调用接口

    // 登录
    /** @var \ND\SDP\UC\User */
    $ucUser = $client->uc->token->login($session, $loginname, $password, $orgCode);

    // 创建组织
    $client->admin->org->create($orgName, $orgCode, $nodeType, ['person_join_type' => 1]);

更多接口请自行尝试,没有的接口参阅[CONTRIBUTING](CONTRIBUTING.md) 提交

### MAC鉴权
对于需要mac鉴权的接口,先设置授权

    $ucUser = new \ND\SDP\UC\User($accountId, 'person', $app, $accessToken, $macKey, $refreshToken)
    $client->setAuth($user);

### BTS鉴权

    $bts = \ND\SDP\BTSApp::get($name, $secret, $id);
    $token = $client->bts->token->get($bts);
    $client->setAuth($token);

### 租户
部分接口是租户接口

    $tenant = \ND\SDP\SdpTenant::byApp($app);
    $data = $client->wallet($tenant)->point->currencies();

## Services
### 敏感词服务
    $client = new SdpClient();
    $client->setAuth($auth);
    $configLoader = new BasicConfigLoader($client, $appId);
    $censorService = new FullMatchCensorService($configLoader);
    $text = "习近平和毛泽东";
    $censorService->testString($text);  // true
    $censorService->highlight($text);  // [[1,2], [4,3], [0,3]]
    $censorService->censor($text);  // ***和***

## [Contributing](#CONTRIBUTING.md)

## [CHANGELOG](CHANGELOG.md)