/*环境变量 所有变化的东西 统一放在此处*/
function ApplicationEnv(){
    this.env = {
        serverPath:"/api/file/upload",
        keyPath:"/systemv/magic/key",
        key:{
            //允许的域名
            domain:"hr.huashan.org.cn",
            secret:"7fbef7faee6969f1f482045ddeea73440cc0b3c782e10498f91958ee659d89b2abbe11bc1f0d4b7dfb76cba4a485fc22fe6ecdcb55671d0e2e7dce6023bb54c9929355967287ad6d4e4e8bff402d5e96ece7a52365b55fc6ae04158e57d4680dada2d5a7476bb3b620614195da17ec261158c088108063982a4715a895425ffb",
        }
    }
}
ApplicationEnv.prototype.getEnv = function () {
    return this.env;
}
var APPLICATION_ENV = new ApplicationEnv();
