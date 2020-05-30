class RowAPAR extends React.Component{
     state     =    {
          view           :    'read',
          isDeleting     :    false,
          jenisAPAR      :    '',
          kodeGedung     :    0,
          lantaiGedung   :    0,
          nomorTabung    :    0,
          keterangan     :    '',
          listKodeGedung :    [],
          showPasswordForm    :    false,
          userLevel :    ''
     }
     showQRCode     =    (dataAPAR)   =>   {
          Swal.fire({
               title     :    'APAR QR Code',
               html      :    `<div class='col-12 text-center'>
                                   <b id='qrCode'></b>
                                   <p id='qrCodeText' class='text-sm mb-1 mt-3'></p>
                              </div>`,
               type      :    'info',
               showConfirmButton   :    true,
               showCancelButton    :    false,
               confirmButtonText   :    'Print',
          }).then((konfirmasi) => {
               if(konfirmasi.value){
                    window.location.href     =    `${basicURL}APAR/print/qrcode/${dataAPAR.qrCode}`;
               }
          });

          new QRCode(document.getElementById('qrCode'), dataAPAR.qrCode);
          $('#qrCodeText').text(dataAPAR.qrCode);
     }
     changeNilaiKriteria     =    (idAPAR, kriteriaValue, kriteriaKey)   =>   {
          let options    =    {params : {idAPAR, kriteriaKey}};
          let tP    =    this.props;

          let ubahNilaiKriteria    =    axios.get(`${basicURL}APAR/ubahNilaiKriteria`, options)
               .then((responseFromServer) => {
                    let statusUbahNilaiKriteria   =    responseFromServer.data.statusUbahNilaiKriteria;
                    if(statusUbahNilaiKriteria){
                         tP.reloadDataAPAR();
                    }
               }).catch((error) => {
                    alert(error);
               })
     }
     edit      =    ()   =>   {
          let getListKodeGedung    =    axios.get(`${basicURL}gedung/listDataGedung`)
               .then((responseFromServer) => {
                    let listKodeGedung  =    responseFromServer.data.listDataGedung;
                    this.setState({listKodeGedung, view : 'update'});
               }).catch((error) => {
                    alert(error);
               });
     }
     componentDidMount   =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;

          this.setState({
               jenisAPAR : data.jenisAPAR, 
               kodeGedung : data.kodeGedung, 
               lantaiGedung : data.lantaiGedung, 
               nomorTabung : data.nomorTabung,
               keterangan : data.keterangan
          });

          $('[data-toggle=tooltip]').tooltip();
     }
     hapus     =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;
          
          let cekLevelUser    =    axios.post(`${basicURL}User/getLevelUser`);
          cekLevelUser.then((responseFromServer) => {
               let getLevelUser    =    responseFromServer.data.getLevelUser;

               // if(getLevelUser !== 'superadmin'){
               //      if(getLevelUser === 'admin'){
               //           this.setState({showPasswordForm : true});
               //      }
               // }else{
               //      this.lanjutkanHapus(data.id, tP);
               // }
               this.setState({userLevel : getLevelUser, showPasswordForm : true});
          });
     }
     lanjutkanHapus =    (idAPAR, tP)   =>   {
          let hapusAPAR     =    axios.post(`${basicURL}APAR/deleteAPAR`, `idAPAR=${idAPAR}`)
               .then((responseFromServer) => {
                    let statusDeleteAPAR   =    responseFromServer.data.statusDeleteAPAR;
                    let swalMessage, swalType;
                    if(statusDeleteAPAR){
                         swalMessage    =    'Berhasil Menghapus Data APAR !';
                         swalType       =    'success';
                    }else{
                         swalMessage    =    'Gagal Menghapus Data APAR !';
                         swalType       =    'error';
                    }

                    Swal.fire({
                         title     :    'Hapus Data APAR',
                         text      :    swalMessage,
                         type      :    swalType
                    }).then(() => {
                         if(statusDeleteAPAR){
                              this.setState({isDeleting : false, view : 'read'});
                              tP.reloadDataAPAR();
                         }
                    })
               }).catch((error) => alert(error));
     }
     simpanDataAPAR =    ()   =>   {
          let tS         =    this.state;
          let tP         =    this.props;
          let data       =    tP.data;
          let btnSubmit    =    $('#simpanDataAPAR');

          let dataPOST  =    `idAPAR=${data.id}&jenisAPAR=${tS.jenisAPAR}&kodeGedung=${tS.kodeGedung}&lantaiGedung=
                              ${tS.lantaiGedung}&nomorTabung=${tS.nomorTabung}&keterangan=${tS.keterangan}`;
          let simpanDataAPAR  =    axios.post(`${basicURL}APAR/editDataAPAR`, dataPOST);
          simpanDataAPAR.then((responseFromServer) => {
               let statusEditAPAR  =    responseFromServer.data.statusEditAPAR;
               if(statusEditAPAR){
                    tP.reloadDataAPAR();
                    this.setState({view : 'read'});
               }else{
                    Swal.fire({
                         title     :    'Penyimpanan Data APAR',
                         text      :    responseFromServer.data.message,
                         type      :    'error'
                    });
               }
          }).catch((error) => alert(error));
     }
     componentDidUpdate  =    (prevProps, prevState)   =>   {
          let tP    =    this.props;
          if(tP.data !== prevProps.data){
               let {jenisAPAR, kodeGedung, lantaiGedung, nomorTabung, keterangan} = tP.data;
               this.setState({jenisAPAR, kodeGedung, lantaiGedung, nomorTabung, keterangan});
          }
     }
     render    =    ()   =>   {
          let tP    =    this.props;
          let tS    =    this.state;
          let data  =    tP.data;
          let index =    tP.index;

          return(
               <tr className={'text-sm'}>
                    {tS.view === 'read' &&
                         <React.Fragment>
                              <td className="vam text-center text-bold">{index}.</td>
                              <td className="vam text-left">
                                   <h6 className={'mb-1'}>{data.qrCode}</h6>
                                   <a href={`${basicURL}APAR/print/qrcode/${data.qrCode}`} target={'_blank'}>
                                        <span className={'material-icons text-success cp'} 
                                             style={{fontSize:'15pt'}} data-toggle={'tooltip'} 
                                             data-placement={'top'}
                                             title={'Print QRCode'}>print</span>
                                   </a>
                              </td>
                              <td className="vam text-left">
                                   <p className={'mb-1'}>
                                        {data.namaGedung} ({data.kodeGedung}) Lantai {data.lantaiGedung}
                                   </p>
                              </td>

                              <td className="vam text-center">
                                   {(data.cond_pressure.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_pressure.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_pressure, 'cond_pressure')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_pressure === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_pressure}</span>
                                   }
                                   {(data.cond_pressure.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_pressure.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_pressure}</span>
                                   }
                                   {(data.cond_pressure.length <= 0) && '-'}
                              </td>
                              <td className="vam text-center">
                                   {(data.cond_nozzle.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_nozzle.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_nozzle, 'cond_nozzle')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_nozzle === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_nozzle}</span>
                                   }
                                   {(data.cond_nozzle.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_nozzle.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_nozzle}</span>
                                   }
                                   {(data.cond_nozzle.length <= 0) && '-'}
                              </td>
                              <td className="vam text-center">
                                   {(data.cond_segel.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_segel.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_segel, 'cond_segel')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_segel === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_segel}</span>
                                   }
                                   {(data.cond_segel.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_segel.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_segel}</span>
                                   }
                                   {(data.cond_segel.length <= 0) && '-'}
                              </td>
                              <td className="vam text-center">
                                   {(data.cond_hose.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_hose.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_hose, 'cond_hose')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_hose === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_hose}</span>
                                   }
                                   {(data.cond_hose.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_hose.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_hose}</span>
                                   }
                                   {(data.cond_hose.length <= 0) && '-'}
                              </td>
                              <td className="vam text-center">
                                   {(data.cond_physically.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_physically.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_physically, 'cond_physically')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_physically === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_physically}</span>
                                   }
                                   {(data.cond_physically.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_physically.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_physically}</span>
                                   }
                                   {(data.cond_physically.length <= 0) && '-'}
                              </td>
                              <td className="vam text-center">
                                   {(data.cond_valve.length >= 1 && tP.useFor === 'listData') &&
                                        <span className={`badge cp badge-${(data.cond_valve.toLowerCase() === 'ok')? 'success' : 'danger'}`}
                                             onClick={() => this.changeNilaiKriteria(data.id, data.cond_valve, 'cond_valve')}
                                             data-toggle={'tooltip'} data-placement={'top'} 
                                             title={`Klik untuk mengubah status menjadi ${(data.cond_valve === 'OK')? 'Not OK' : 'OK'}`}>{data.cond_valve}</span>
                                   }
                                   {(data.cond_valve.length >= 1 && tP.useFor === 'laporan') && 
                                        <span className={`badge cp badge-${(data.cond_valve.toLowerCase() === 'ok')? 'success' : 'danger'}`}>{data.cond_valve}</span>
                                   }
                                   {(data.cond_valve.length <= 0) && '-'}
                              </td>
                              <td className="vam text-left">
                                   {(data.keterangan.length >= 1)? data.keterangan : '-'}
                              </td>
                              <td className="vam text-left">
                                   {(data.lastUpdate === '0000-00-00 00:00:00')? 'Belum Pernah Diupdate' : data.lastUpdate}
                              </td>
                              {tP.useFor === 'listData' &&
                                   <td className="vam text-center">
                                        <span className={'text-success material-icons cp icon-option mr-1'}
                                             data-toggle='tooltip' data-placement='top' title='Edit Data'
                                             onClick={() => this.edit()}>edit</span>
                                        <span className={'text-danger material-icons cp icon-option ml-1'}
                                             data-toggle='tooltip' data-placement='top' title='Hapus Data' 
                                             onClick={() => this.setState({view : 'delete'})}>delete</span>
                                   </td>
                              }
                         </React.Fragment>
                    }
                    {tS.view === 'update' &&
                         <td colSpan={12} className={'vam py-4 px-3'}>
                              <h6>Edit Data Asset</h6>
                              <div className={'row'}>
                                   <div className={'col-lg-3 col-md-3 col-sm-6 col-xs-12'}>
                                        <div className={'form-group'}>
                                             <label htmlFor={'jenisAPAR'} className={''}>Jenis Asset *</label>
                                             <select name={'jenisAPAR'} id={'jenisAPAR'}
                                                  className={'form-control'} value={tS.jenisAPAR} 
                                                  onChange={(e) => this.setState({jenisAPAR : e.target.value})}>
                                                  <option value={'PAR'}>APAR</option>
                                                  <option value={'PAB'}>APAB</option>
                                                  <option value={'PLR'}>Hydrant Pillar</option>
                                                  <option value={'HBX'}>Hydrant Box</option>
                                             </select>
                                        </div>
                                   </div>
                                   <div className={'col-lg-6 col-md-6 col-sm-12 col-xs-12'}>
                                        <label htmlFor={'lokasi'} className={''}>Gedung dan Lantai Lokasi (Lantai Max. 99) *</label>
                                        <div className={'input-group'}>
                                             <select className={'form-control'} value={tS.kodeGedung}
                                                  onChange={(e) => this.setState({kodeGedung : e.target.value})}>
                                                  <option value={''}>-- Pilih Gedung --</option>
                                                  {tS.listKodeGedung.map((gedung, indexGedung) => {
                                                       return    <option value={gedung.kodeGedung} key={indexGedung}>
                                                                      {gedung.namaGedung}
                                                                 </option>;
                                                  })}
                                             </select>
                                             <input className={'form-control'} type={'text'} 
                                                  value={tS.lantaiGedung} placeholder={'Lantai'}
                                                  onChange={(e) => {
                                                       let value      =    e.target.value;
                                                       this.setState({lantaiGedung : (value.length >= 3)? 99 : value})
                                                  }} />
                                        </div>
                                   </div>
                                   <div className={'col-lg-3 col-md-3 col-sm-6 col-xs-12'}>
                                        <label htmlFor={'nomorTabung'} className={''}>Nomor Tabung (Max. 999) *</label>
                                        <input className={'form-control'} type={'text'} id={'nomorTabung'}
                                             value={tS.nomorTabung} placeholder={'Nomor Tabung'}
                                             onChange={(e) => {
                                                  let value      =    e.target.value;
                                                  this.setState({nomorTabung : (value.length >= 4)? 999 : value})}
                                                  } />
                                   </div>
                                   <div className={'col-12'}>
                                        <label htmlFor={'keterangan'} className={''}>Keterangan Asset </label>
                                        <textarea type={'text'} name={'keterangan'} id={'keterangan'} placeholder={'Keterangan Asset'}
                                             className={'form-control'} value={tS.keterangan} 
                                             onChange={(e) => this.setState({keterangan : e.target.value})}></textarea>
                                   </div>
                              </div>
                              <hr />
                              <button className={'btn btn-success btn-pill mr-2'} id={'simpanDataAPAR'} 
                                   onClick={() => this.simpanDataAPAR()}>Simpan Perubahan Data</button>
                              <button className={'btn btn-danger btn-pill'} onClick={() => this.setState({view : 'read'})}>Batal</button>
                         </td>
                    }
                    {tS.view === 'delete' &&
                         <td colSpan={12} className={'text-center vam py-5'}>
                              {!tS.showPasswordForm &&
                                   <React.Fragment>
                                        <img src={(tS.isDeleting)? `${basicURL}assets/img/loading.gif` : `${basicURL}assets/img/delete.svg`} 
                                             alt={'Delete Gedung'} className={'img-block mb-3'} 
                                             style={{width:'220px'}} />
                                        {tS.isDeleting === true &&
                                             <p className={'mb-3 mt-2 text-danger'}>Processing, wait a moment please ..</p>
                                        }
                                        {tS.isDeleting === false &&
                                             <p className={'mb-3 mt-2 text-danger'}>Anda yakin akan menghapus data APAR ini ?</p>
                                        }
                                        <button className={'btn btn-danger btn-pill mr-1'} onClick={() => this.hapus()}
                                             id={'lanjutkanHapus'} disabled={tS.isDeleting}>{(tS.isDeleting)? 'Processing' : 'Lanjutkan Hapus'}</button>
                                        {tS.isDeleting === false &&
                                             <button className={'btn btn-success btn-pill ml-1'} 
                                                  onClick={() => this.setState({view : 'read'})}>Batal</button>
                                        }
                                   </React.Fragment>
                              }
                              {tS.showPasswordForm &&
                                   <AdministratorPrivileges response={(boolValue) => {
                                        if(boolValue === true){
                                             this.lanjutkanHapus(data.id, tP);
                                        }else{
                                             Swal.fire({
                                                  title     :    'Administrator Privileges',
                                                  html      :    `Password Administrator Salah !`,
                                                  type      :    'error'
                                             });
                                        }
                                   }} cancel={() => this.setState({showPasswordForm : false, view : 'read'})}
                                   userLevel={tS.userLevel} />
                              }
                         </td>
                    }
               </tr>
          );
     }
}

RowAPAR.defaultProps     =    {
     useFor     :    'listData'
}