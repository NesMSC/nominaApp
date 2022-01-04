 <template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Respaldos</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section> 
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-light">
                            <i class="fa fa-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tamaño</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(backup, index) in arrayBackup" :key="index">
                                        <td v-text="backup.file_name"></td>
                                        <td v-text="backup.file_size"></td>
                                        <td>
                                            <a 
                                                href="#" style="color:#fff;" 
                                                class="btn btn-success btn-sm" 
                                                @click.prevent="download(backup.file_name)"
                                            >
                                                <i class="fas fa-file-download"></i>
                                            </a>

                                            <a 
                                                href="#" 
                                                class="btn btn-danger btn-sm text-light" 
                                                @click.prevent="deleteBackup(backup.file_name)"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
    
</template>

<script>
export default {
    data(){
        return {
            arrayBackup: [],
        }
    },
    methods: {
        getBackups(){
            let me = this;

            axios.get('/backup').then((response)=>{

                me.arrayBackup = response.data;
            })
        }
    },
    mounted(){
        this.getBackups()
    }
}
</script>