<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> 栏目列表
        </div>
        <div class="container">
            <div class="handle-box">
                <a-button type="primary" @click="addView()" class="mr10"><template #icon><PlusOutlined /></template>创建</a-button>
                <a-input v-model:value="search" placeholder="栏目名称" class="handle-input mr10"></a-input>
                  <a-button type="primary" @click="handleSearch">
                    <template #icon><SearchOutlined /></template>搜索
                </a-button>
            </div>
            <!-- banner列表 -->
			<a-table :columns="columns" :data-source="menuAll" :pagination="false" :rowKey='record=>record.id'>
                <template #img_url="{ record }">
                    <div><img style="width:100px;heigth:100px" :src="record.img_url" /></div>
                </template>

                <template #recommend="{ recommend,record }">
                    <a-popconfirm placement="topRight" ok-text="是" cancel-text="否"  @confirm="recommendOperation(record)">
                        <template  #title>
                            <p v-if="record.recommend == 1">隐藏此菜单？</p>
                            <p v-else>显示此菜单？</p>
                        </template>
                        <a-tag  :color="record.recommend == 1 ? 'green' :'red' " > {{record.recommend == 1 ?'正常' :"禁用" }}</a-tag>
                    </a-popconfirm>
                </template>

                <template #status="{ status,record }">
                    <a-switch checked-children="正常" un-checked-children="隐藏" v-model:checked="record.status" @click="statusOperation(record)"  />
                </template>

				<!-- 按钮 -->
				<template #operation="{record,index }">
					<div class="editable-row-operations">
                        <a-button size="small" type="link"  @click="sonView(record)"> <template #icon><PlusOutlined /></template>添加子菜单</a-button>
						<a-button size="small" type="link" @click="editView(record)" ><template #icon><EditOutlined /></template>编辑</a-button>
                        <a-popconfirm placement="topRight" ok-text="是" cancel-text="否" @confirm="delMenu(record)">
							<template #title>
								<p>确定删除?</p>
							</template>
							<a-button size="small" type="link"> <template #icon><DeleteOutlined /></template>删除</a-button>
						</a-popconfirm>
					</div>
				</template>

			</a-table>
        </div>

        <!--模态窗 -->
        <template>
            <div>
                <a-modal 
                    :title="ModalText"
                    :visible="visible"
                    :confirm-loading="confirmLoading"
                    :centered="true"
                    :maskClosable="false"
                    @ok="handleSubmits"
                    width="50%"
                    :zIndex="99"
                    @cancel="handleCancel">
                    <a-form :model="form" :label-col="labelCol" :wrapper-col="wrapperCol">
                        <a-form-item label="菜单名称" >
                            <a-input v-model:value="form.menu_name" placeholder="菜单名称" />
                        </a-form-item>
                        <a-form-item label="菜单标识" >
                            <a-input v-model:value="form.menu_sign" placeholder="菜单标识" />
                        </a-form-item>

                        <a-form-item label="所属组" >
                            <a-select v-model:value="form.group_sign" ref="select">
                                <a-select-option v-for="group in groupAll" :value="group.group_sign"> {{group.group_name}}</a-select-option>
                            </a-select>
                        </a-form-item>

                        <a-form-item label="上级菜单" v-if="is_son">
                            <a-input v-model:value="topname" placeholder="顶级菜单" :disabled="true" />
                        </a-form-item>

                        <a-form-item label="图片">
                            <a-upload
                                name="file"
                                action="http://comprehensive.cn/api/admin/upload/uploadImageSingle"
                                list-type="picture"
                                :default-file-list="imgList"
                                @change="handleChange"
                                >
                                <a-button> <CloudUploadOutlined />上传 </a-button>
                            </a-upload>
                        </a-form-item>

                        <a-form-item label="排序" >
                            <a-input v-model:value="form.sort" placeholder="排序" />
                        </a-form-item>

                        <a-form-item label="状态">
                            <a-radio-group v-model:value="form.status">
                                <a-radio :value="1">显示</a-radio>
                                <a-radio :value="0">隐藏</a-radio>
                            </a-radio-group>
                        </a-form-item>

                        <a-form-item label="是否推荐">
                            <a-radio-group v-model:value="form.recommend">
                                <a-radio :value="1">是</a-radio>
                                <a-radio :value="0">否</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-form>
                </a-modal>
            </div>
        </template>






    </div>
</template>


<script>
const columns = [
	{
		title: '名称',
		dataIndex: 'menu_name',
		slots: { customRender: 'menu_name' },
		align: 'center'
    },  
	{
		title: '图片',
		dataIndex: 'img_url',
		slots: { customRender: 'img_url' },
		align: 'center'
    },  
    {
        title: '菜单标识',
        dataIndex: 'menu_sign',
        slots: { customRender: 'menu_sign' },
        align: 'center'
    },
    {
        title: '所属组',
        dataIndex: 'group_name',
        slots: { customRender: 'group_name' },
        align: 'center'
    },
    {
        title: '排序',
        dataIndex: 'sort',
        slots: { customRender: 'sort' },
        align: 'center'
    },
    {
        title: '推荐',
        dataIndex: 'recommend',
        slots: { customRender: 'recommend' },
        align: 'center'
    },
    {
        title: '状态',
        dataIndex: 'status',
        slots: { customRender: 'status' },
        align: 'center'
    },
	{
		title: '创建时间',
		dataIndex: 'add_time',
		slots: { customRender: 'add_time' },
		align: 'center'
    },
	{
		title: '操作',
		key: 'operation',
		slots: { customRender: 'operation' },
		align: 'center'
	},
];

import {fetchPost,fetchGet} from '../../utils/http'
import {
    CloudUploadOutlined,
    PlusOutlined,
    DeleteOutlined,
    SearchOutlined,
    EditOutlined,
    AppstoreOutlined
} from '@ant-design/icons-vue';

const forms = {
    status:1,
    recommend:0,
    pid:0,
    sort:0
} 

export default {
    components: {
        CloudUploadOutlined,
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        SearchOutlined,
        AppstoreOutlined,
    },
	data() {
		return {
            form:{},
            menuAll:[], //列表
			columns,
			search:"", //搜索
			labelCol: { span: 4 },
			wrapperCol: { span: 18 },
			ModalText:'', //模态窗标题
			visible: false, //模态窗状态
			confirmLoading: false,

            topname:"",
            groupAll:{},
            is_son:false,

            imgList:[], //图片

		};
    },

    mounted() {
        this.form = JSON.parse(JSON.stringify(forms));
        this.getData();
        this.getGroup();
    },
   

	methods: {
		getData() {
            let url = '/admin/menu';
            let that = this
			let params = {};
				params.search = this.search;
            fetchGet(url,params).then(res => {
                var ret = res.data;
				this.menuAll = ret.data;
            })
        },

        getGroup(){
            let url = '/admin/menuGroup/getMenuGroup';
            let that = this
            fetchGet(url).then(res => {
                var ret = res.data;
				this.groupAll = ret.data;
            })
        },

        //图片操作
		handleChange(info) {
			if (info.file.status === 'done') {
				if(info.file.response.code == 0){
                    this.$message.error('上传失败');
                    return false;
                }
                this.$message.success('上传成功');
                this.form.img_url = info.file.response.data;
			} else if (info.file.status === 'error') {
                this.$message.error('上传失败');
                return false;
			}
        },

        //搜索
		handleSearch(e){
			this.getData();
		},

		//分页
		onPageChange(page){
			this.pagination.current = page;
			this.pagination.pageNo = page;
			this.getData();
        },

        //添加窗口
        addView(){
            this.ModalText="添加菜单";
            this.visible = true;
        },

        //子菜单窗口
        sonView(row){
            this.is_son = true;
            this.ModalText="添加子菜单";
            this.visible = true;
            this.form.pid = row.id;
            this.topname = row.menu_name;
        },

        //编辑窗口
        editView(row){
            this.ModalText = "编辑";
            this.visible = true;

            this.form.id = row.id;
            this.form.menu_name = row.menu_name;
            this.form.menu_sign = row.menu_sign;
            this.form.img_url = row.img_url;
            this.form.pid = row.pid;
            this.form.status = row.status;
            this.form.recommend = row.recommend;
            this.form.sort = row.sort;

            //封面图片
            let imgUrl = {};
            let pos = row.img_url.lastIndexOf('\/'); // 查找最后一个/的位置
                imgUrl.name =  row.img_url.substring(pos + 1);
                imgUrl.img_url = row.img_url;
            this.imgList.push(imgUrl);

        },

        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/admin/menu/updateMenu';
			}else{
				url = '/admin/menu/createMenu';
			}
            fetchPost(url,this.form).then(res => {
                var ret = res.data;
                if(ret.code == 1){
					this.handleCancel();
                    this.$message.success(ret.msg);
                    this.getData();
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //关闭窗口
        handleCancel(){
            this.visible = false;
            this.topname = '';
            this.imgList.pop();
            this.form = {};
            this.form = JSON.parse(JSON.stringify(forms));
        },

        //删除
        delMenu(row){
            let url = '/admin/menu/delMenu';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    this.getData()
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //操作
        statusOperation(row){
            let url = '/admin/menu/statusOperation';
 			let params = {};
                params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    row.status = row.status == 1? 0 : 1;
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        recommendOperation(row){
            let url = '/admin/menu/statusOperation';
 			let params = {};
                params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    row.recommend = row.recommend == 1? 0 : 1;
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        }

	},
};
</script>
