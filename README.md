# 数据库ORM
db组件是基于PDO链式数据库操作的ORM框架

#开始使用

####安装组件
使用 composer 命令进行安装或下载源代码使用(依赖willphp/config组件)。

    composer require willphp/db

> WillPHP 框架已经内置此组件，无需再安装。

####调用示例

    \willphp\db\Db::query("select * from wp_test where id=:id AND title=:title", ['id'=>11,'title'=>'r10']);

####数据库配置

`config/database.php`配置文件设置如下：
	
	//默认配置		
	'default' => [
		'db_type' => 'mysqli', //数据库驱动类型
		'db_host' => 'localhost', //数据库服务器
		'db_port' => '3306', //服务器端口
		'db_user' => 'root', //数据库用户名
		'db_pwd' => '', //数据库密码
		'db_name' => 'myapp01db', //数据库名
		'table_pre' => 'wp_', //数据库表前缀
		'db_charset' => 'utf8', //默认字符编码
		'pdo_params' => [
			\PDO::ATTR_CASE => \PDO::CASE_NATURAL,
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_ORACLE_NULLS => \PDO::NULL_NATURAL,
			\PDO::ATTR_STRINGIFY_FETCHES => false,
			\PDO::ATTR_EMULATE_PREPARES => false,
		], //PDO连接参数
	],
	//数据库读服务器
	'read' => [
		'db_host' => '127.0.0.1', 
	],
	//使用sqlite数据库
	'sqlite' => [
		'dsn' => 'sqlite:'.__DIR__.'/../db/mydb.db', 
	],
	

####连接配置

通过connect方法连接配置操作DB：

	Db::connect('sqlite')->table('test')->select();
	Db::connect(['db_host'=>'127.0.0.1'], 'test')->select();

####预准备操作

组件支持使用预准备查询，可以完全避免SQL注入。

    Db::execute("update test set total=:total where id=:id",['total'=>6,'id'=>1]);
    Db::query("select * from wp_test where id=? AND title=?", [11, 'willphp']);

####数据查询

表别名 | alias

    Db::table('users')->alias('a'); //或Db::table(['users' => 'a'])

 字段设置 | field

    Db::table('users')->field('id,username')->find(); //支持数组['id', 'username']

条件设置 | where

    $vo = Db::table('users')->where('id', 1)->where('status', 1)->find(); //查询id=1 AND status=1的数据

数据排序 | order

    $list = Db::table('users')->order('id ASC')->select(); //使用id升序排列

数据分页 | limit，page

    $list = Db::table('users')->limit(0,10)->select(); //从第1条开始，获取10条数据

总记录数 | count

    $count = Db::table('users')>where('status', 1)->count(); //获取status=1条件的记录数

字段总和 | sum

    $count = Db::table('users')->where('status', 1)->sum('fen'); //获取status=1条件的fen字段总和

字段最小值 | min

    $count = Db::table('users')->where('status', 1)->min('fen'); //获取status=1条件的fen字段最小值

字段最大值 | max

    $count = Db::table('users')->where('status', 1)->max('fen'); //获取status=1条件的fen字段最大值

字段平均值 | avg

    $count = Db::table('users')->where('status', 1)->avg('fen'); //获取status=1条件的fen字段平均值

单条数据 | find

    $vo = Db::table('users')->where('id>1')->find(); //获取id>1的第一行数据

多条数据 | select

    $list = Db::table('users')->where('status', 1)->select(); //获取status=1条件的多行数据

获取字段值 | getField

    $var = Db::table('users')->where('id', 1)->getField('username'); //返回id=1的username值    
    $arr = Db::table('users')->where('status', 1)->getField('id,username'); //返回[id => username]格式的数组

从字段获取 | getBy[字段]

    Db::table('users')->getByUsername('willphp');

分页处理 | paginate

    $obj = Db::table('users')->where('status', 1)->paginate(10); 
    foreach ($obj as $vo ) {
    	dump($vo['username']);
    }
    echo $obj->links(); //显示分页html	 

获取SQL | getSql 

    $sql = Db::table('users')->where('id', '=', 1, 'or')->where('id', 2)->where('status',1)->getSql()->select();  
    echo $sql; //显示：SELECT * FROM `wp_users` WHERE (`id`=1 OR `id`=2) AND `status`=1  

关联查询 | join

    Db::table('test a')->join('demo b', 'a.pid=b.id', 'left')->select();  

####数据增改

 数据新增 | insert，replace，insertGetId，insertAll

    Db::table('users')->insert($data); //$data为数组['字段名'=>值]

数据更新 | update, data

    Db::table('users')->where('id', 1)->update($data); //返回影响的记录数
    Db::table('users')->where('id', 1)->data($data)->update(); 

字段更新 | setField

    Db::table('users')->where('id', 1)->setField('username', 'test');  //更新id=1的username字段

统计增加 | setInc，inc

    Db::table('users')->where('id', 1)->setInc('score', 100); //score增加100
    Db::table('users')->where('id', 1)->inc('score', 100)->update(); //score增加100

统计减少 | setDec，dec

    Db::table('users')->where('id', 1)->setDec('score'); //score减少1

####数据删除

数制删除 | delete

    Db::table('users')->where('id', 2)->delete(); //删除id=2的数据
    Db::table('users')->delete([1,2]); //删除id=1和2的数据

####其他操作

    union //查询 union
    group //设置group查询
    having //设置having查询
    using //USING支持 用于多表删除
    extra //设置查询的额外参数
    duplicate //设置DUPLICATE
    lock //查询lock
    distinct //distinct查询
    force //指定强制索引
    comment //查询注释

####事务支持

    Db::startTrans(); //启动事务
    $r1 =Db::table('demo')->add(['cname'=>'r10']);
    $r2 = Db::table('test')->where('id', 1)->setField('fen', 1);
    if ($r1 && $r2) {			
    	Db::commit(); //提交事务
    	echo '提交成功';
    } else {
    	Db::rollback(); //事务回滚
    	echo '提交失败';
    }
    //或使用：
    Db::transaction(function() {
		Db::table('demo')->add(['cname'=>'r10']);
    	Db::table('test')->where('id', 1)->setField('fen', 1);			
    });

####助手函数

    //格式：db('表名', '配置')
    db('users')->find();


####where条件

where方法常用参数设置，格式如下：

    where('字段名','[查询表达式]','查询条件值','[连接方式]');

查询表达式

查询表达式不分大小写，支持的查询表达式有：

    where('id', '=', 1)             //等于，同 where('id', 1) 
    where('id', '<>', 1)            //不等于
    where('id', '>', 1)             //大于
    where('id', '<', 1)             //小于
    where('id', '>=', 1)            //大于等于
    where('id', '<=', 1)            //小于等于
    where('id', 'in', '1,2,3')      //IN查询(not in)，支持数组[1,2,3]
    where('na', 'like', '%ad%')     //模糊查询(not like) 
    where('id', 'between', '1,3')   //区间查询(not between)
    where('id', 'exp', 'IN (2,3)')  //表达式查询，支持SQL语法 

where(字符串)

示例代码：

    Db::table('users')->where('id=1 AND status=1')->find(); 
    //sql：SELECT * FROM `wp_users` WHERE id=1 AND status=1 LIMIT 1

where(一维数组)

示例代码：

    Db::table('users')->where(['id'=>1,'status'=>1])->find(); //[字段名=>值]只能用AND连接
    //sql：SELECT * FROM `wp_users` WHERE `id`=1 AND `status`=1 LIMIT 1

where(二维数组)

示例代码：

    $map = [];
    $map[] = ['id', '=', 1, 'or']; //条件1
    $map[] = ['username', 'like', '%admin%']; //条件2
    $map['status'] = 1; //条件3       
    Db::table('users')->where($map)->select();
    //sql：SELECT * FROM `wp_users` WHERE (`id`=1 OR `username` LIKE '%admin%') AND `status`=1

where(字段名,值)

示例代码：

    Db::table('users')->where('id', 1)->find(); //查询 字段名=值 时使用
    //sql：SELECT * FROM `wp_users` WHERE `id`=1 LIMIT 1

where(字段名,表达式,条件)

示例代码：

    Db::table('users')->where('id', 'in', [1,2,3])->where('status', '>', 0)->select(); //用表达式来查询
    //sql：SELECT * FROM `wp_users` WHERE `id` IN (1,2,3) AND `status`>0

where(字段名,表达式,条件,or)

示例代码：

    Db::table('users')->where('id', '=', 1, 'or')->where('id', 2)->where('status',1)->select(); 
    //sql：SELECT * FROM `wp_users` WHERE (`id`=1 OR `id`=2) AND `status`=1