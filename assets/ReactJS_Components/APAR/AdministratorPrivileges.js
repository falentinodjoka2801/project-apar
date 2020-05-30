class AdministratorPrivileges extends React.Component{
     state     =    {
          password  :    ''
     }
     cek      =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          let options    =    `password=${tS.password}&userLevel=${tP.userLevel}`;
          let cekPasswordAdministrator =    axios.post(`${basicURL}User/cekPasswordAdministrator`, options)
               .then((responseFromServer) => {
                    let cekPasswordAdministrator   =    responseFromServer.data.cekPasswordAdministrator;

                    tP.response(cekPasswordAdministrator);
               }).catch((error) => {
                    alert(error);
               });
     }
     render    =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          return(
               <div className={'col-lg-6 col-md-6 offset-lg-3 offset-md-3 col-sm-12 col-xs-12 pb-4'}>
                    <img src={`${basicURL}assets/img/superadmin.png`} alt={'Admin Privileges'} className={'img-block'} 
                         style={{width:'200px'}} />
                    <input type={'password'} placeholder={`${(tP.userLevel === 'superadmin')? 'Password Login Anda':'Password Administator'}`} 
                         className={'text-center form-control'} value={tS.password}
                         onChange={(e) => this.setState({password : e.target.value})} />
                    <hr />
                    <button className={'btn btn-outline-success btn-pill mr-1'} onClick={() => this.cek()}>Lanjutkan</button>
                    <button className={'btn btn-danger btn-pill ml-1'} onClick={() => tP.cancel()}>Batalkan</button>
               </div>
          );
              
     }
}