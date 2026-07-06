using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace Vuoto.Pages
{
    public class ChiSiamoModel : PageModel
    {
        public void OnGet()
        {
            ViewData["Missione"] = "Missione di questo sito";
        }
    }
}
