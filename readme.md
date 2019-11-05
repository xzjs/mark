# 案例标记系统
## 安装
1. 安装PHP依赖
`composer install`
2. 安装js依赖
`npm install`
3. 复制配置文件
`cp .env.example .env`
4. 生成key
`php artisan key:generate`
5. 创建软链
`php artisan storage:link`
6. 执行数据库迁移
`php artisan migrate`