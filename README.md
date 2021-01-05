# 网龙工程院共享平台(SDP) PHP SDK

由于代码早先封装,之后移动了结构,可能导致命名空间之类的异常,如有发现,可告知修改.

## Installation
### 直接安装
1. 登录 http://git.sdp.nd
2. 将本机的ssh密钥添加至 http://git.sdp.nd/profile/keys

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

## Contribute

## [CHANGELOG](CHANGELOG.md)