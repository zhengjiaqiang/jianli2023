/*授权Key*/
function Key(){
    var env = APPLICATION_ENV.getEnv();
    /*全局设置*/
        this.security = {
            /*软件运行允许的域名：防破解 具体配置在application-env.js*/
            domain:env.key.domain,
            /*此key来自www.magicalcoder.com提供 具体配置在application-env.js*/
            secret:env.key.secret,
            /*是否允许调试：false 加强破解难度*/
            debug:true,
            /*是否允许控制台输出：false 加强破解难度*/
            console:true,
            /*防别人下载后直接运行客户端 此地址是解析加密算法的地址 */
            activeUrl:env.keyPath,
            /*请随机给36位长度的纯字母数字组合*/
            random:"623224023783266990398957217534766630",
            //ip不校验 直接通过 不配置则不通过
            ipPass:true
        }
 }
 Key.prototype.getSecurity = function () {
     return this.security;
 }
