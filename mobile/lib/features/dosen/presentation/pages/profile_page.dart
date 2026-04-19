import 'package:flutter/material.dart';
import 'package:mobile/core/services/storage_service.dart';
import 'package:mobile/shared/widgets/dosen/bottom_nav.dart';
import 'package:mobile/shared/widgets/mahasiswa/glass_card.dart';

class DosenProfilePage extends StatefulWidget {
  const DosenProfilePage({super.key});

  @override
  State<DosenProfilePage> createState() => _DosenProfilePageState();
}

class _DosenProfilePageState extends State<DosenProfilePage> {
  final _currentIndex = 2;

  void _onNavTapped(int index) {
    if (index == _currentIndex) return;

    switch (index) {
      case 0:
        Navigator.pushReplacementNamed(context, '/dosen/dashboard');
        break;

      case 1:
        Navigator.pushReplacementNamed(context, '/dosen/kelas');
        break;

      case 2:
        Navigator.pushReplacementNamed(context, '/dosen/profile');
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = {
      "name": "Eric Prakarsa Putra",
      "email": "eric@itbss.ac.id",
      "nidn": "10000001",
      "role": "dosen"
    };

    return Scaffold(
      backgroundColor: const Color(0xFFF1F5F9),
      bottomNavigationBar: BottomNav(
        currentIndex: _currentIndex,
        onTap: _onNavTapped,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [

            /// 🔵 HEADER
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(20, 60, 20, 50),
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7C3AED), Color(0xFF4F46E5)],
                ),
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(40),
                  bottomRight: Radius.circular(40),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [

                  /// 🔙 BACK + TITLE
                  Row(
                    children: [
                      GestureDetector(
                        onTap: () {
                          if (Navigator.of(context).canPop()) {
                          Navigator.of(context).pop();
                          } else {
                          // Jika tidak bisa back (stack kosong), arahkan ke dashboard
                          Navigator.pushReplacementNamed(context, '/dosen/dashboard');
                          }
                        },
                        child: Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.arrow_back, color: Colors.white),
                        ),
                      ),
                      const SizedBox(width: 12),
                      const Text(
                        "Profile",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                        ),
                      )
                    ],
                  ),

                  const SizedBox(height: 20),

                  /// 👤 PROFILE INFO
                  Row(
                    children: [
                      Container(
                        width: 80,
                        height: 80,
                        decoration: BoxDecoration(
                          color: Colors.white.withValues(alpha: 0.2),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: Colors.white.withValues(alpha: 0.3)),
                        ),
                        child: const Icon(Icons.person, size: 40, color: Colors.white),
                      ),
                      const SizedBox(width: 16),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            user['name']!,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 20,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          Text(
                            user['email']!,
                            style: const TextStyle(color: Colors.white70),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.white.withValues(alpha: 0.2),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              "NIDN: ${user['nidn']}",
                              style: const TextStyle(color: Colors.white),
                            ),
                          )
                        ],
                      )
                    ],
                  )
                ],
              ),
            ),

            const SizedBox(height: 16),

            /// 🔥 CONTENT
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [

                  /// 📋 INFO CARD
                  GlassCard(
                    child: Column(
                      children: [
                        _buildItem(Icons.person, "Nama Lengkap", user['name']!),
                        _divider(),
                        _buildItem(Icons.email, "Email", user['email']!),
                        _divider(),
                        _buildItem(Icons.badge, "NIDN", user['nidn']!),
                        _divider(),
                        _buildItem(Icons.person_outline, "Role", user['role']!),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  /// ⚙️ SETTINGS
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      "Pengaturan",
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                  ),

                  const SizedBox(height: 10),

                  GlassCard(
                    child: Row(
                      children: const [
                        Icon(Icons.key, color: Colors.deepPurpleAccent),
                        SizedBox(width: 10),
                        Expanded(child: Text("Ubah Password")),
                        Icon(Icons.chevron_right),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),

                  /// 🔴 LOGOUT
                  GestureDetector(
                    onTap: () async {
                      await StorageService.removeAll();

                      if (!context.mounted) return;

                      Navigator.pushNamedAndRemoveUntil(
                        context,
                        '/login',
                            (route) => false,
                      );
                    },
                    child: Container(
                      width: double.infinity,
                      height: 55,
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(
                          colors: [Colors.red, Colors.redAccent],
                        ),
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.red.withValues(alpha: 0.3),
                            blurRadius: 20,
                            offset: const Offset(0, 10),
                          )
                        ],
                      ),
                      child: const Center(
                        child: Text(
                          "Logout",
                          style: TextStyle(
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),

                  const SizedBox(height: 40),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  /// 🔹 ITEM BUILDER
  Widget _buildItem(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        children: [
          Icon(icon, color: Colors.deepPurpleAccent),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: const TextStyle(fontSize: 12, color: Colors.grey)),
                Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _divider() {
    return const Divider(height: 1);
  }
}