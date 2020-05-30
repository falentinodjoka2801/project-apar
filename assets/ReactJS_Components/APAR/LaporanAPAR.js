class LaporanAPAR extends React.Component{
     state     =    {
          listDataAPAR   :    [],
          listDataAPARImmutable    :    [],
          jenisAPARSelected        :    'PAR'
     }
     terapkanFilter      =    ()   =>   {
          let tS         =    this.state;
          let btnFilter  =    $('#btnFilter');

          btnFilter.prop('disabled', true).text('Processing ..');

          let options    =    {params : {jenisAPAR : tS.jenisAPARSelected}};

          let getListDataAPAR =    axios.get(`${basicURL}APAR/listAPARRusak`, options)
               .then((responseFromServer) => {
                    let listAPAR   =    responseFromServer.data.listAPARRusak;

                    this.setState({
                         listDataAPAR             :    listAPAR,
                         listDataAPARImmutable    :    listAPAR
                    });
                    
                    btnFilter.prop('disabled', false).text('Terapkan Filter');
               }).catch((error) => {
                    alert(error);
               });
     }
     render    =    ()   =>   {
          let tS    =    this.state;

          let rows;

          let jumlahPhysicallyRusak     =    0;
          let jumlahHoseRusak           =    0;
          let jumlahNozzleRusak         =    0;
          let jumlahSegelRusak          =    0;
          let jumlahValveRusak          =    0;
          let jumlahPressureRusak       =    0;

          if(tS.listDataAPAR.length >= 1){
               rows      =    tS.listDataAPAR.map((dataAPAR, index) => {
                                   // let jenisAPAR  =    dataAPAR.jenisAPAR.toLowerCase();
                                   let jenisAPAR       =    tS.jenisAPARSelected.toLowerCase();

                                   if(jenisAPAR  === 'par' || jenisAPAR === 'pab'){
                                        jumlahPressureRusak      +=    (dataAPAR.cond_pressure.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahNozzleRusak        +=    (dataAPAR.cond_nozzle.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahSegelRusak         +=    (dataAPAR.cond_segel.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahHoseRusak          +=    (dataAPAR.cond_hose.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahPhysicallyRusak    +=    (dataAPAR.cond_physically.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahValveRusak         +=    0;
                                   }else if(jenisAPAR === 'plr'){
                                        jumlahPressureRusak      +=    0;
                                        jumlahNozzleRusak        +=    0;
                                        jumlahSegelRusak         +=    0;
                                        jumlahHoseRusak          +=    0;
                                        jumlahPhysicallyRusak    +=    (dataAPAR.cond_physically.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahValveRusak         +=    (dataAPAR.cond_valve.toLowerCase() === 'not ok')? 1 : 0;
                                   }else if(jenisAPAR === 'hbx'){
                                        jumlahPressureRusak      +=    0;
                                        jumlahNozzleRusak        +=    (dataAPAR.cond_nozzle.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahSegelRusak         +=    0;
                                        jumlahHoseRusak          +=    (dataAPAR.cond_hose.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahPhysicallyRusak    +=    (dataAPAR.cond_physically.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahValveRusak         +=    (dataAPAR.cond_valve.toLowerCase() === 'not ok')? 1 : 0;
                                   }else{
                                        jumlahPressureRusak      +=    (dataAPAR.cond_pressure.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahNozzleRusak        +=    (dataAPAR.cond_nozzle.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahSegelRusak         +=    (dataAPAR.cond_segel.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahHoseRusak          +=    (dataAPAR.cond_hose.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahPhysicallyRusak    +=    (dataAPAR.cond_physically.toLowerCase() === 'not ok')? 1 : 0;
                                        jumlahValveRusak         +=    (dataAPAR.cond_valve.toLowerCase() === 'not ok')? 1 : 0;
                                   }

                                   return    <RowAPAR key={index} index={index+1} data={dataAPAR} useFor={'laporan'} />
                              });
          }else{
               rows      =    <TRDataNotFound title={'Data Tidak Ditemukan'} desc={'Data tidak ditemukan berdasarkan pencarian !'} 
                                   colSpan={10} />;
          }
          
     
          return(
               <div className="row">
                    <div className="col-lg-12">
                         <div className="card">
                              <div className="card-header border-bottom">
                                   <div className="row">
                                        <div className="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left">Laporan Data Asset</div>
                                        <div className="col-lg-8 col-md-8 col-sm-6 col-xs-6 text-right">

                                        </div>
                                   </div>
                              </div>
                              <div className="card-body">
                                   <div className={'row'}>
                                        <div className={'col-12'}>
                                             <div className={'form-group'}>
                                                  <label htmlFor={'Jenis APAR'} className={'text-sm'}>Jenis Asset</label>
                                                  <select name={'jenisAPAR'} id={'jenisAPAR'} className={'form-control form-control-md mb-3'}
                                                       value={tS.jenisAPARSelected} onChange={(e) => this.setState({jenisAPARSelected : e.target.value})}>
                                                       <option value={'all'}>Semua Jenis APAR/PAB/HYD/HBX</option>
                                                       <option value={'PAB'}>APAB</option>
                                                       <option value={'PAR'}>APAR</option>
                                                       <option value={'PLR'}>Hydrant Pillar</option>
                                                       <option value={'HBX'}>Hydran Box</option>
                                                  </select>
                                                  <button className={'btn btn-outline-success btn-sm btn-pill'} id={'btnFilter'} 
                                                       onClick={() => this.terapkanFilter()}>
                                                       Terapkan Filter
                                                  </button>
                                             </div>
                                        </div>
                                   </div>
                                   {tS.listDataAPAR.length >= 1 &&
                                        <React.Fragment>
                                             <hr />
                                             <div className="table-responsive mt-3">
                                                  <table className="table table-bordered table-striped" id='tabelAPAR'>
                                                       <thead>
                                                            <tr>
                                                                 <th className="vam text-center" rowSpan={2}>No.</th>
                                                                 <th className="vam text-center" rowSpan={2}>KKS</th>
                                                                 <th className="vam text-center" rowSpan={2}>Lokasi</th>
                                                                 <th className="vam text-center" colSpan={6}>Kriteria</th>
                                                                 <th className="vam text-left" rowSpan={2}>Keterangan</th>
                                                                 <th className="vam text-left" rowSpan={2}>Last Update</th>
                                                            </tr>
                                                            <tr>
                                                                 <th className="vam text-center">Pressure</th>
                                                                 <th className="vam text-center">Nozzle</th>
                                                                 <th className="vam text-center">Segel</th>
                                                                 <th className="vam text-center">Hose</th>
                                                                 <th className="vam text-center">Physically</th>
                                                                 <th className="vam text-center">Valve</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            {rows}
                                                       </tbody>
                                                  </table>
                                             </div>
                                             <hr />
                                             <div className={'row'}>
                                                  <div className={'col-12 text-left'}>
                                                       <span className={'mb-1 badge badge-primary ml-1 mr-1'}>Jumlah Pressure Rusak : {jumlahPressureRusak}</span>
                                                       <span className={'mb-1 badge badge-success ml-1 mr-1'}>Jumlah Nozzle Rusak : {jumlahNozzleRusak}</span>
                                                       <span className={'mb-1 badge badge-info ml-1 mr-1'}>Jumlah Segel Rusak : {jumlahSegelRusak}</span>
                                                       <span className={'mb-1 badge badge-warning ml-1 mr-1'}>Jumlah Hose Rusak : {jumlahHoseRusak}</span>
                                                       <span className={'mb-1 badge badge-danger ml-1 mr-1'}>Jumlah Physically Rusak : {jumlahPhysicallyRusak}</span>
                                                       <span className={'mb-1 badge badge-light ml-1 mr-1'}>Jumlah Valve Rusak : {jumlahValveRusak}</span>
                                                  </div>
                                             </div>
                                             <hr />
                                             <div className={'row'}>
                                                  <div className={'col-12'}>
                                                       <a href={`${basicURL}APAR/exportData/pdf/laporanAPARRusak/${tS.jenisAPARSelected}`}>
                                                            <button className={'btn btn-outline-success btn-pill'}>Export Data</button>
                                                       </a>
                                                  </div>
                                             </div>
                                        </React.Fragment>
                                   }
                              </div>
                         </div>
                    </div>
               </div>
          );
              
     }
}