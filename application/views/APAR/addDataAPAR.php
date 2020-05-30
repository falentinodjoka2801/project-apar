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
     class AddDataAPAR extends React.Component{
          state     =    {
               jenisAPAR      :    'PAR',
               kodeGedung     :    '',
               lantaiGedung   :    '',
               nomorTabung    :    '',
               kriteria  :    {
                    pressure  :    'OK',
                    nozzle    :    'OK',
                    segel     :    'OK',
                    hose      :    'OK',
                    physically     :    'OK',
                    valve     :    'OK'
               },
               keterangan     :    '',
               listKodeGedung :    []
          }
          componentDidMount   =    ()   =>   {
               let getListKodeGedung    =    axios.get(`${basicURL}gedung/listDataGedung`)
                    .then((responseFromServer) => {
                         let listKodeGedung  =    responseFromServer.data.listDataGedung;
                         this.setState({listKodeGedung});
                    }).catch((error) => {
                         alert(error);
                    });
          }
          simpanDataAPAR      =    ()   =>   {
               let tS    =    this.state;
               let el    =    $('#simpanDataAPAR');

               el.prop('disabled', true);
               el.text('Processing ..');

               $.ajax({
                    url  :    `${basicURL}APAR/addAPAR`,
                    type :    'POST',
                    data :    tS,
                    success   :    (responseFromServer) => {
                         let JSONResponse    =    JSON.parse(responseFromServer);

                         let type, message, html;
                         if(JSONResponse.addAPAR === true){
                              type      =    'success';
                              message   =    'Berhasil menyimpan data asset !';
                              html      =    `<div class='col-12 text-center'>
                                                  <h5 class='mb-2'>QR Code</h5>
                                                  <b id='qrCode'></b>
                                                  <p class='mb-2 mt-3 text-success'>${JSONResponse.message}</p>
                                             </div>`;
                         }else{
                              type      =    'error';
                              message   =    JSONResponse.message;
                              html      =    `<b class='text-danger'>${message}</b>`;
                         }

                         Swal.fire({
                              title     :    'Penyimpanan Data Asset',
                              html,
                              type,
                              allowOutsideClick   :    false,
                              showConfirmButton   :    JSONResponse.addAPAR,
                              confirmButtonText   :    'Print',
                              showCancelButton    :    true,
                              cancelButtonText    :    'Go to List Asset'
                         }).then((konfirmasi) => {
                              if(konfirmasi.value){
                                   window.location.href     =    `${basicURL}APAR/print/qrcode/${JSONResponse.message}`;
                              }else{
                                   window.location.href     =    `${basicURL}APAR`;
                              }
                         });

                         if(JSONResponse.addAPAR === true){
                              new QRCode(document.getElementById('qrCode'), JSONResponse.message);
                         }
                         
                         el.prop('disabled', false);
                         el.text('Simpan Data');
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
                                             <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left">Tambah Data Asset</div>
                                             <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right">
                                                  <a href={`${basicURL}APAR`}>
                                                       <span className={'material-icons cp text-danger'}>close</span>
                                                  </a>
                                             </div>
                                        </div>
                                   </div>
                                   <div className="card-body">
                                        <div className={'row'}>
                                             <div className={'col-lg-8 col-md-8 col-sm-12 col-xs-12 border-right mt-2'}>
                                                  <h5 className={'mb-4'}>Data Asset</h5>
                                                  <div className={'row mb-3'}>
                                                       <div className={'col-lg-4 col-md-4 col-sm-12 col-xs-12'}>
                                                            <label htmlFor={'jenisAPAR'} className={'text-sm'}>Jenis Asset *</label>
                                                            <select name={'jenisAPAR'} id={'jenisAPAR'}
                                                                 className={'form-control'} value={tS.jenisAPAR} 
                                                                 onChange={(e) => this.setState({jenisAPAR : e.target.value})}>
                                                                 <option value={'PAR'}>APAR</option>
                                                                 <option value={'PAB'}>APAB</option>
                                                                 <option value={'PLR'}>Hydrant Pillar</option>
                                                                 <option value={'HBX'}>Hydrant Box</option>
                                                            </select>
                                                       </div>
                                                       <div className={'col-lg-8 col-md-8 col-sm-12 col-xs-12'}>
                                                            <label htmlFor={'lokasi'} className={'text-sm'}>Gedung dan Lantai Lokasi (Lantai Max. 99) *</label>
                                                            <div className={'input-group'}>
                                                                 <select className={'form-control'} value={tS.kodeGedung}
                                                                      onChange={(e) => {
                                                                           let value      =    e.target.value;
                                                                           if(value.toLowerCase() === 'addgedung'){
                                                                                window.location.href     =    `${basicURL}/gedung/addGedung`;
                                                                           }else{
                                                                                this.setState({kodeGedung : value});
                                                                           }
                                                                      }}>
                                                                      <option value={''}>-- Pilih Gedung --</option>
                                                                      {tS.listKodeGedung.map((gedung, indexGedung) => {
                                                                           return    <option value={gedung.kodeGedung} key={indexGedung}>
                                                                                          {gedung.namaGedung}
                                                                                     </option>;
                                                                      })}
                                                                      <option value={'addGedung'} style={{fontWeight:'bold', color : 'blue'}}>TAMBAH Gedung</option>
                                                                 </select>
                                                                 <input className={'form-control'} type={'text'} 
                                                                      value={tS.lantaiGedung} placeholder={'Lantai'}
                                                                      onChange={(e) => {
                                                                           let value      =    e.target.value;
                                                                           this.setState({lantaiGedung : (value.length >= 3)? 99 : value})
                                                                      }} />
                                                            </div>
                                                       </div>
                                                  </div>
                                                  <div className={'row mb-3'}>
                                                       <div className={'col-12'}>
                                                            <label htmlFor={'nomorTabung'} className={'text-sm'}>Nomor Tabung (Max. 999) *</label>
                                                            <input className={'form-control'} type={'text'} id={'nomorTabung'}
                                                                 value={tS.nomorTabung} placeholder={'Nomor Tabung'}
                                                                 onChange={(e) => {
                                                                      let value      =    e.target.value;
                                                                      this.setState({nomorTabung : (value.length >= 4)? 999 : value})}
                                                                  } />
                                                       </div>
                                                  </div>
                                                  {false &&
                                                       <div className={'row mb-3'}>
                                                            <div className={'col-12 mt-2'}>
                                                                 <label htmlFor={'keterangan'} className={'text-sm'}>Keterangan APAR </label>
                                                                 <textarea type={'text'} name={'keterangan'} id={'keterangan'} placeholder={'Keterangan APAR'}
                                                                      className={'form-control'} value={tS.keterangan} 
                                                                      onChange={(e) => this.setState({keterangan : e.target.value})}></textarea>
                                                            </div>
                                                       </div>
                                                  }
                                             </div>
                                             <div className={'col-lg-4 col-md-4 col-sm-12 col-xs-12 mt-2'}>
                                                  <h5 className={'mb-4'}>Kriteria</h5>
                                                  <div className={'row mt-3'}>
                                                       {(tS.jenisAPAR === 'PAR' || tS.jenisAPAR === 'PAB') &&
                                                            <div className={'col-lg-12'}>
                                                                 <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                      <input type="checkbox" id="criteriaPressure" name="criteriaPressure" 
                                                                           className="custom-control-input" value={tS.kriteria.pressure}
                                                                           checked={(tS.kriteria.pressure === 'OK')? true : false}
                                                                           onChange={(e) => {
                                                                                let pressure   =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                                let kriteria   =    tS.kriteria;
                                                                                kriteria['pressure']     =    pressure;

                                                                                this.setState({kriteria});
                                                                           }}/>
                                                                      <label className={`custom-control-label`} 
                                                                           htmlFor="criteriaPressure">Pressure
                                                                           <span className={`ml-2 badge ${(tS.kriteria.pressure === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                                {tS.kriteria.pressure}
                                                                           </span>
                                                                      </label>
                                                                 </div>
                                                            </div>
                                                       }
                                                       {tS.jenisAPAR !== 'PLR' &&
                                                            <div className={'col-lg-12'}>
                                                                 <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                      <input type="checkbox" id="criteriaNozzle" name="criteriaNozzle" 
                                                                           className="custom-control-input" value={tS.kriteria.nozzle}
                                                                           checked={(tS.kriteria.nozzle === 'OK')? true : false}
                                                                           onChange={(e) => {
                                                                                let nozzle     =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                                let kriteria   =    tS.kriteria;
                                                                                kriteria['nozzle']     =    nozzle;

                                                                                this.setState({kriteria});
                                                                           }}/>
                                                                      <label className={`custom-control-label`} 
                                                                           htmlFor="criteriaNozzle">Nozzle
                                                                           <span className={`ml-2 badge ${(tS.kriteria.nozzle === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                                {tS.kriteria.nozzle}
                                                                           </span>
                                                                      </label>
                                                                 </div>
                                                            </div>
                                                       }
                                                       {(tS.jenisAPAR === 'PAR' || tS.jenisAPAR === 'PAB') &&
                                                            <div className={'col-lg-12'}>
                                                                 <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                      <input type="checkbox" id="criteriaSegel" name="criteriaSegel" 
                                                                           className="custom-control-input" value={tS.kriteria.segel}
                                                                           checked={(tS.kriteria.segel === 'OK')? true : false}
                                                                           onChange={(e) => {
                                                                                let segel     =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                                let kriteria   =    tS.kriteria;
                                                                                kriteria['segel']     =    segel;

                                                                                this.setState({kriteria});
                                                                           }}/>
                                                                      <label className={`custom-control-label`} 
                                                                           htmlFor="criteriaSegel">Segel
                                                                           <span className={`ml-2 badge ${(tS.kriteria.segel === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                                {tS.kriteria.segel}
                                                                           </span>
                                                                      </label>
                                                                 </div>
                                                            </div>
                                                       }
                                                       {tS.jenisAPAR !== 'PLR' &&
                                                            <div className={'col-lg-12'}>
                                                                 <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                      <input type="checkbox" id="criteriaHose" name="criteriaHose" 
                                                                           className="custom-control-input" value={tS.kriteria.hose}
                                                                           checked={(tS.kriteria.hose === 'OK')? true : false}
                                                                           onChange={(e) => {
                                                                                let hose       =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                                let kriteria   =    tS.kriteria;
                                                                                kriteria['hose']     =    hose;

                                                                                this.setState({kriteria});
                                                                           }}/>
                                                                      <label className={`custom-control-label`} 
                                                                           htmlFor="criteriaHose">Hose
                                                                           <span className={`ml-2 badge ${(tS.kriteria.hose === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                                {tS.kriteria.hose}
                                                                           </span>
                                                                      </label>
                                                                 </div>
                                                            </div>
                                                       }
                                                       <div className={'col-lg-12'}>
                                                            <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                 <input type="checkbox" id="criteriaPhysically" name="criteriaPhysically" 
                                                                      className="custom-control-input" value={tS.kriteria.physically}
                                                                      checked={(tS.kriteria.physically === 'OK')? true : false}
                                                                      onChange={(e) => {
                                                                           let physically       =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                           let kriteria   =    tS.kriteria;
                                                                           kriteria['physically']     =    physically;

                                                                           this.setState({kriteria});
                                                                      }}/>
                                                                 <label className={`custom-control-label`} 
                                                                      htmlFor="criteriaPhysically">Physically
                                                                      <span className={`ml-2 badge ${(tS.kriteria.physically === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                           {tS.kriteria.physically}
                                                                      </span>
                                                                 </label>
                                                            </div>
                                                       </div>
                                                       {(tS.jenisAPAR === 'PLR' || tS.jenisAPAR === 'HBX') &&
                                                            <div className={'col-lg-12'}>
                                                                 <div className="custom-control custom-toggle custom-toggle-sm mb-1">
                                                                      <input type="checkbox" id="criteriaValve" name="criteriaValve" 
                                                                           className="custom-control-input" value={tS.kriteria.valve}
                                                                           checked={(tS.kriteria.valve === 'OK')? true : false}
                                                                           onChange={(e) => {
                                                                                let valve      =    (e.target.value === 'OK')? 'Not OK' : 'OK';
                                                                                let kriteria   =    tS.kriteria;
                                                                                kriteria['valve']     =    valve;

                                                                                this.setState({kriteria});
                                                                           }}/>
                                                                      <label className={`custom-control-label`} 
                                                                           htmlFor="criteriaValve">Valve
                                                                           <span className={`ml-2 badge ${(tS.kriteria.valve === 'OK')? 'badge-success' : 'badge-danger'}`}>
                                                                                {tS.kriteria.valve}
                                                                           </span>
                                                                      </label>
                                                                 </div>
                                                            </div>
                                                       }
                                                  </div>
                                             </div>
                                        </div>
                                        <hr />
                                        <div className={'row'}>
                                             <div className={'col-12'}>
                                                  <button className={'btn btn-success btn-pill mr-1'} onClick={() => this.simpanDataAPAR()}
                                                       id={'simpanDataAPAR'}>Simpan Data</button>
                                                  <a href={`${basicURL}APAR`}>
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

     ReactDOM.render(<AddDataAPAR />, document.getElementById('root'));

     $('[data-toggle="tooltip"]').tooltip();
</script>