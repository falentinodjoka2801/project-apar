class TRDataNotFound extends React.Component{
     render    =    ()   =>   {
          let tP    =    this.props;

          return(
               <tr>
                    <td colSpan={tP.colSpan} className={'text-center'}>
                         <h3 className={'text-danger mt-3'}>{tP.title}</h3>
                         <img src={`${basicURL}assets/img/empty.svg`} alt={'Data Not Found'} className={'mt-3 mb-3'} 
                              style={{width:'250px'}} />
                         <p className={'text-danger'}>{tP.desc}</p>
                    </td>
               </tr>
          );
     }
}

TRDataNotFound.defaultProps      =    {
     title     :    'Data Not Found',
     desc      :    'Data Not Found',
     colSpan   :    2
}