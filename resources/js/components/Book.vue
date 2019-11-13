<template>
    <div>
        <el-button v-if="user.permissions.indexOf('book.write')!==-1" type="primary" icon="el-icon-edit"
                   @click="dialogVisible=true">添加
        </el-button>
        <el-table
                border
                :data="books"
                style="width: 100%">
            <el-table-column
                    prop="id"
                    label="编号"
                    width="50px">
            </el-table-column>
            <el-table-column
                    prop="topic"
                    label="题目">
            </el-table-column>
            <el-table-column
                    prop="legends"
                    label="图例">
                <template slot-scope="scope">
                    <el-image v-for="legend in scope.row.legends" :src="legend.url+'-small'" fit="contain"></el-image>
                </template>
            </el-table-column>
            <el-table-column
                    v-if="user.permissions.indexOf('book.write')!==-1"
                    fixed="right"
                    label="操作"
                    width="100">
                <template slot-scope="scope">
                    <el-button @click="edit(scope.$index, scope.row)" size="small">修改</el-button>
                    <br>
                    <el-button @click="del(scope.$index, scope.row)" type="danger" size="small">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="pagination">
            <el-pagination background layout="prev, pager, next" :total="total"
                           :page-size="10"
                           @current-change="currentChange"
                           @prev-click="currentChange"
                           @next-click="nextClick"
            ></el-pagination>
        </div>
        <el-dialog
                title="添加案例"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="book" :rules="rule" ref="form">
                <el-form-item label="题目" label-width="100px" prop="topic">
                    <el-input
                            type="textarea"
                            :rows="2"
                            placeholder="请输入题目内容"
                            v-model="book.topic">
                    </el-input>
                </el-form-item>
                <el-form-item label="图例" label-width="100px" prop="legend">
                    <el-upload
                            ref="legend"
                            list-type="picture-card"
                            action="/upload"
                            name="img"
                            accept="image/jpeg,image/png"
                            :limit=10
                            :file-list="book.legends"
                            :on-success="uploadLegendSuccess"
                            :on-remove="deleteLegendSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-plus"></i>
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
                total: 0,
                book: {
                    topic: '',
                    legends: [],
                },
                books: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    topic: [{required: true, message: '请输入案例题目', trigger: 'blur'}],
                }
            }
        },
        methods: {
            uploadLegendSuccess(response, file, fileList) {
                this.book.legends = fileList;
            },
            deleteLegendSuccess(file, fileList) {
                this.book.legends = fileList;
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.book = {};
                        this.$refs.legend.clearFiles();
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        if (this.book.id) {
                            axios.put('/books/' + this.book.id, this.book)
                                .then(response => {
                                    this.refresh();
                                })
                                .catch(error => {
                                    this.$message.error(error.response.data);
                                })
                        } else {
                            axios.post('/books', this.book)
                                .then(response => {
                                    this.refresh();
                                })
                                .catch((error) => {
                                    this.$message.error(error.response.data);
                                })
                        }
                    } else {
                        return false;
                    }
                });
            },
            refresh() {
                this.getBooks();
                this.book = {};
                this.$refs.form.resetFields();
                this.$refs.legend.clearFiles();
                this.book.legends = [];
                this.dialogVisible = false;
            },
            getBooks(params) {
                axios.get('books', {params: params})
                    .then((response) => {
                        this.books = response.data.data;
                        this.total = response.data.total;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            del(index, row) {
                axios.delete('/books/' + row.id)
                    .then(response => {
                        this.getBooks();
                    })
                    .catch(error => {
                        console.log(error);
                    })

            },
            edit(index, row) {
                this.book = row;
                this.dialogVisible = true;
            },
            currentChange(page) {
                this.getBooks({'page': page});
            },
            preCLick(page) {
                if (page > 1) {
                    this.getBools({'page': page - 1});
                }
            },
            nextClick(page) {
                this.getBooks({'page': page + 1});
            }
        },
        mounted() {
            this.getBooks();
        }
    }
</script>

<style scoped>
    .pagination {
        text-align: center;
        margin: 20px;
    }
</style>
