class RowGedung extends React.Component{
     state     =    {
          view      :    'read',
          kodeGedung     :    '',
          namaGedung     :    '',
          confirmImg     :    '',
          isDeleting     :    false
     }
     componentDidMount   =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;

          this.setState({kodeGedung : data.kodeGedung, namaGedung : data.namaGedung});
          $('[data-toggle=tooltip]').tooltip();
     }
     simpanPerubahan     =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          let btnSimpan  =    $('#simpanPerubahan');
          let data  =    {kodeGedung : tS.kodeGedung, namaGedung : tS.namaGedung};

          btnSimpan.prop('disabled', true);
          btnSimpan.text('Processing ..');

          $.ajax({
               url  :    `${basicURL}Gedung/addDataGedung`,
               data,
               type :    'POST',
               success   :    (responseFromServer)     =>   {
                    let JSONResponse    =    JSON.parse(responseFromServer);

                    let type, message;
                    if(JSONResponse.addDataGedung === true){
                         type      =    'success';
                         message   =    'Berhasil menyimpan data gedung !';
                    }else{
                         type      =    'error';
                         message   =    'Gagal menyimpan data gedung !';
                    }

                    Swal.fire({
                         title     :    'Penyimpanan Data Gedung',
                         type,
                         html      :    `<b class='${(type === 'success')? 'text-success' : 'text-danger'}'>${message}</b>`
                    }).then(() => {
                         btnSimpan.prop('disabled', false);
                         btnSimpan.text('Simpan');

                         
                         if(JSONResponse.addDataGedung === true){
                              this.setState({view : 'read'})
                              tP.reloadDataGedung();
                         }else{
                              this.state({
                                   view      :    'read',
                                   kodeGedung     :    tP.data.kodeGedung,
                                   namaGedung     :    tP.data.namaGedung
                              });
                         }
                    });
               }
          });
     }
     hapus     =    ()   =>   {
          let tP    =    this.props;
          let data  =    tP.data;

          this.setState({isDeleting : true});
          
          let hapusGedung     =    axios.post(`${basicURL}gedung/deleteDataGedung`, `kodeGedung=${data.kodeGedung}`)
               .then((responseFromServer) => {
                    let statusDeleteGedung   =    responseFromServer.data.statusDeleteGedung;
                    let swalMessage, swalType;
                    if(statusDeleteGedung){
                         swalMessage    =    'Berhasil Menghapus Data Gedung !';
                         swalType       =    'success';
                    }else{
                         swalMessage    =    'Gagal Menghapus Data Gedung !';
                         swalType       =    'error';
                    }

                    Swal.fire({
                         title     :    'Hapus Data Gedung',
                         text      :    swalMessage,
                         type      :    swalType
                    }).then(() => {
                         if(statusDeleteGedung){
                              this.setState({isDeleting : false, view : 'read'});
                              tP.reloadDataGedung();
                         }
                    })
               }).catch((error) => alert(error));
     }
     render    =    ()   =>   {
          let tP    =    this.props;
          let tS    =    this.state;
          let data  =    tP.data;
          let index =    tP.index;

          return(
               <tr className={'text-sm'}>
                    {(tS.view === 'read' || tS.view === 'update') &&
                         <React.Fragment>
                              <td className="vam text-center text-bold">{index}.</td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.kodeGedung}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.kodeGedung} onChange={(e) => this.setState({kodeGedung : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Kode Gedung'} readOnly={true} />
                                   }
                              </td>
                              <td className="vam text-left">
                                   {tS.view === 'read' && data.namaGedung}
                                   {tS.view === 'update' &&
                                        <input type={'text'} value={tS.namaGedung} onChange={(e) => this.setState({namaGedung : e.target.value})}
                                             className={'form-control form-control-sm'} placeholder={'Kode Gedung'} />
                                   }
                              </td>
                              {tS.view === 'read' &&
                                   <td className="vam text-center">
                                        <span className={'text-success material-icons cp icon-option mr-1'}
                                             data-toggle='tooltip' data-placement='top' title='Edit Data' 
                                             onClick={() => this.setState({view : 'update'})}>edit</span>
                                        <span className={'text-danger material-icons cp icon-option ml-1'}
                                             data-toggle='tooltip' data-placement='top' title='Hapus Data'
                                             onClick={() => this.setState({view : 'delete'})}>delete</span>
                                   </td>
                              }
                              {tS.view === 'update' &&
                                   <td className="vam text-center">
                                        <button onClick={() => this.simpanPerubahan()}
                                             className={'btn btn-sm btn-outline-success btn-pill mr-1'} 
                                             id={'simpanPerubahan'}>Simpan</button>
                                        <button onClick={() => this.setState({view : 'read'})} 
                                             className={'btn btn-sm btn-danger btn-pill ml-1'}
                                             onClick={() => this.setState({view : 'read'})}>Cancel</button>
                                   </td>
                              }
                         </React.Fragment>
                    }
                    {tS.view === 'delete' &&
                         <td colSpan={4} className={'text-center py-5'}>
                              <img src={(tS.isDeleting)? `${basicURL}assets/img/loading.gif` : `${basicURL}assets/img/delete.svg`} 
                                   alt={'Delete Gedung'} className={'img-block mb-3'} 
                                   style={{width:'220px'}} />
                              {tS.isDeleting === true &&
                                   <p className={'mb-3 mt-2 text-danger'}>Processing, wait a moment please ..</p>
                              }
                              {tS.isDeleting === false &&
                                   <p className={'mb-3 mt-2 text-danger'}>Anda yakin akan menghapus data gedung ini ?</p>
                              }
                              <button className={'btn btn-danger btn-pill mr-1'} onClick={() => this.hapus()}
                                   id={'lanjutkanHapus'} disabled={tS.isDeleting}>{(tS.isDeleting)? 'Processing' : 'Lanjutkan Hapus'}</button>
                              {tS.isDeleting === false &&
                                   <button className={'btn btn-success btn-pill ml-1'} 
                                        onClick={() => this.setState({view : 'read'})}>Batal</button>
                              }
                         </td>
                    }
               </tr>
          );
     }
}