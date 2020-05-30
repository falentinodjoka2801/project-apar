<?php $this->load->view('components/head'); ?>
     <body class="h-100">
          <div class="container-fluid">
               <div class="row">
                    <?php $this->load->view('components/sidebar'); ?>
                    <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
                         <?php $this->load->view('components/navbar'); ?>
                         <div class="main-content-container container-fluid px-4 pb-4">
                              <?php 
                                   // $dataPageTitle =    [
                                   //      'pageTitleTitle'    =>   'Add APAR',
                                   //      'pageTitleSubTitle' =>   'Tambah Data APAR'
                                   // ];
                                   // $this->load->view('components/page-title', $dataPageTitle); 
                              ?>
                              <div id="root">
                              </div>
                         </div>
                         <?php $this->load->view('components/footer'); ?>
                    </main>
               </div>
          </div>
     </body>
</html>

<script src="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.js')?>"></script>
<link href="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.css')?>" rel='stylesheet' />

<script src="<?=base_url('assets/Shards/qr-code/qrcode.min.js')?>"></script>

<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/common/basicURL.js')?>"></script>

<script type='text/babel'>
     class AddDataGedung extends React.Component{
          state     =    {
               kodeGedung     :    '',
               namaGedung     :    ''
          }
          simpanDataGedung      =    ()   =>   {
               let tS    =    this.state;
               let el    =    $('#simpanDataGedung');

               el.prop('disabled', true);
               el.text('Processing ..');

               $.ajax({
                    url  :    `${basicURL}gedung/addDataGedung`,
                    type :    'POST',
                    data :    tS,
                    success   :    (responseFromServer) => {
                         let JSONResponse    =    JSON.parse(responseFromServer);

                         let type, message;
                         if(JSONResponse.addDataGedung === true){
                              type      =    'success';
                              message   =    'Berhasil menyimpan data Gedung !';
                         }else{
                              type      =    'error';
                              message   =    JSONResponse.message;
                         }

                         Swal.fire({
                              title     :    'Penyimpanan Data Gedung',
                              html      :    `<b class='${(type === 'success')? 'text-success' : 'text-danger'}'>${message}</b>`,
                              type
                         }).then(() => {
                              el.prop('disabled', false);
                              el.text('Simpan Data');

                              if(JSONResponse.addDataGedung === true){
                                   window.location.href     =    '<?=site_url("Gedung")?>';
                              }
                         });
                    }
               })
          }
          render    =    ()   =>   {
               let tS                   =    this.state;
               
               return(
                    <div className="row">
                         <div className="col-lg-12">
                              <div className="card">
                                   <div className="card-header border-bottom">
                                        <div className="row">
                                             <div className="col-10 text-left col">Tambah Data Gedung</div>
                                             <div className="col-2 text-right col">
                                                  <a href={`${basicURL}Gedung`}>
                                                       <span className={'material-icons cp text-danger'}>close</span>
                                                  </a>
                                             </div>
                                        </div>
                                   </div>
                                   <div className="card-body">
                                        <div className={'row'}>
                                             <div className={'col-12 border-right mt-2'}>
                                                  <h5 className={'mb-4'}>Data Gedung</h5>
                                                  <div className={'row mb-3'}>
                                                       <div className={'col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2'}>
                                                            <label htmlFor={'kodeGedung'} className={'text-sm'}>Kode Gedung *</label>
                                                            <input type={'text'} name={'kodeGedung'} id={'kodeGedung'} 
                                                                 placeholder={'Kode Gedung'}
                                                                 className={'form-control'} value={tS.kodeGedung} 
                                                                 onChange={(e) => this.setState({kodeGedung : e.target.value})} />
                                                       </div>
                                                       <div className={'col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-2'}>
                                                            <label htmlFor={'namaGedung'} className={'text-sm'}>Nama Gedung *</label>
                                                            <input type={'text'} name={'namaGedung'} id={'namaGedung'} 
                                                                 placeholder={'Nama Gedung'}
                                                                 className={'form-control'} value={tS.namaGedung} 
                                                                 onChange={(e) => this.setState({namaGedung : e.target.value})} />
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <hr />
                                        <div className={'row'}>
                                             <div className={'col-12'}>
                                                  <button className={'btn btn-success btn-pill mr-1'} onClick={() => this.simpanDataGedung()}
                                                       id={'simpanDataGedung'}>Simpan Data Gedung</button>
                                                  <a href={`${basicURL}Gedung`}>
                                                       <button className={'btn btn-danger btn-pill ml-1 btn-outline-danger'}>Batal</button>
                                                  </a>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               );
          }
     }

     ReactDOM.render(<AddDataGedung />, document.getElementById('root'));

     $('[data-toggle="tooltip"]').tooltip();
</script>