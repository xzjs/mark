<template>
    <div>
        <el-button v-if="user.type === 0" type="primary" icon="el-icon-edit" @click="dialogVisible=true">添加</el-button>
        <el-table
                border
                :data="books"
                style="width: 100%">
            <el-table-column
                    prop="name"
                    label="书名"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="path"
                    label="路径">
                <template slot-scope="scope">
                    <a :href="scope.row.path" target="_blank">查看</a>
                </template>
            </el-table-column>
            <el-table-column
                    v-if="user.type === 0"
                    prop="users"
                    label="分配用户">
                <template slot-scope="scope">
                    <li v-for="bookUser in scope.row.users">
                        {{bookUser.name}}
                    </li>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
                title="添加图书"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="book" :rules="rule" ref="form">
                <el-form-item label="图书名称" label-width="100px" prop="name">
                    <el-input v-model="book.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="图书上传" label-width="100px" prop="path">
                    <el-upload
                            ref="upload"
                            class="upload-demo"
                            drag
                            action="/upload"
                            name="img"
                            accept=".pdf"
                            :on-success="uploadSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                        <div class="el-upload__tip" slot="tip">只能上传pdf文件</div>
                    </el-upload>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="create">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Book",
        computed: {
            ...mapState(['user'])
        },
        data() {
            return {
                book: {
                    name: '',
                    path: '',
                },
                books: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    name: [{required: true, message: '请输入书名', trigger: 'blur'}],
                    path: [{required: true, message: '请上传pdf', trigger: 'blur'}],
                }
            }
        },
        methods: {
            uploadSuccess(response, file, fileList) {
                this.book.path = response;
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.book = {};
                        this.$refs.upload.clearFiles();
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios.post('/books', this.book)
                            .then((response) => {
                                this.getBooks();
                                this.$refs.form.resetFields();
                                this.$refs.upload.clearFiles();
                                this.dialogVisible = false;
                            })
                    } else {
                        return false;
                    }
                });
            },
            getBooks() {
                axios.get('books')
                    .then((response) => {
                        this.books = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        },
        mounted() {
            this.getBooks();
        }
    }
</script>

<style scoped>

</style>